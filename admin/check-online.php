<?php
session_start();
if (!isset($_SESSION['pass'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit;
}

if (!isset($_POST['chapter_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No chapter ID provided.']);
    exit;
}

$chapter_id = intval($_POST['chapter_id']);

include '../db.php';

// Fetch the chapter content
$q = $db->prepare("SELECT content FROM chapters WHERE id = ?");
$q->bind_param('i', $chapter_id);
$q->execute();
$q->bind_result($content);
$q->fetch();
$q->close();

if (empty($content)) {
    echo json_encode(['status' => 'error', 'message' => 'Document has no text content to check.']);
    $db->close();
    exit;
}

// ---------------------------------------------------------
// SIMULATED ONLINE PLAGIARISM CHECK (VIA WIKIPEDIA API)
// ---------------------------------------------------------
// Note: In a production environment, you would replace this block 
// with an API call to a service like Copyleaks or PlagiarismCheck.

// Clean content and split into sentences
$clean_content = preg_replace('/[^a-zA-Z0-9\s\.]/', '', $content);
$sentences = explode('.', $clean_content);

// Filter out very short sentences
$valid_sentences = [];
foreach ($sentences as $sentence) {
    $sentence = trim($sentence);
    if (str_word_count($sentence) > 5) {
        $valid_sentences[] = $sentence;
    }
}

if (count($valid_sentences) == 0) {
    // Not enough text
    $score = 0;
} else {
    // Pick up to 5 random sentences to check against Wikipedia to avoid rate limits
    $sentences_to_check = $valid_sentences;
    if (count($sentences_to_check) > 5) {
        shuffle($sentences_to_check);
        $sentences_to_check = array_slice($sentences_to_check, 0, 5);
    }

    $plagiarized_count = 0;

    foreach ($sentences_to_check as $sentence) {
        // Prepare the Wikipedia API search URL
        // Using exactly quoted string to find exact matches
        $query = urlencode('"' . substr($sentence, 0, 300) . '"'); 
        $url = "https://en.wikipedia.org/w/api.php?action=query&list=search&srsearch={$query}&utf8=&format=json";
        
        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'PMS PlagiarismChecker/1.0 (Local Testing)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $data = json_decode($response, true);
            // If the query returns search results, it means the phrase was found online
            if (isset($data['query']['search']) && count($data['query']['search']) > 0) {
                $plagiarized_count++;
            }
        }
    }

    // Calculate percentage based on samples checked
    $score = round(($plagiarized_count / count($sentences_to_check)) * 100);
}

// Ensure the score is between 0 and 100
$score = max(0, min(100, $score));

// ---------------------------------------------------------
// COMPUTE INTERNAL HYBRID SIMILARITY (TF-IDF + SBERT)
// ---------------------------------------------------------
$max_internal_score = 0;
$bc = str_split($content, 150);
if (count($bc) > 0) {
    $end = count($bc)-1;
    $mid = intdiv($end, 2);
    $qr1 = "%".$bc[0]."%";
    $qr2 = "%".$bc[$mid]."%";
    $qr3 = "%".$bc[$end]."%";

    $q_cand = $db->prepare("SELECT id, content FROM chapters WHERE (content LIKE ? OR content LIKE ? OR content LIKE ?) AND id != ?");
    $q_cand->bind_param('sssi', $qr1, $qr2, $qr3, $chapter_id);
    $q_cand->execute();
    $q_cand->bind_result($c_id, $c_content);

    $candidateContents = [];
    while ($q_cand->fetch()) {
        $candidateContents[$c_id] = $c_content;
    }
    $q_cand->close();

    if (!empty($candidateContents)) {
        include_once '../originality.php';
        $lexicalScores = OriginalityChecker::computeTfidfSimilarities($content, $candidateContents);
        $semanticScores = OriginalityChecker::computeSbertSimilarities($content, $candidateContents);

        foreach ($candidateContents as $c_id => $c_text) {
            $lexScore = isset($lexicalScores[$c_id]) ? $lexicalScores[$c_id] : 0;
            $semScore = isset($semanticScores[$c_id]) ? $semanticScores[$c_id] : 0;
            $hybridScore = round(($lexScore + $semScore) / 2);
            if ($hybridScore > $max_internal_score) {
                $max_internal_score = $hybridScore;
            }
        }
    }
}

// Combine online and internal scores (take the maximum severity)
$final_score = max($score, $max_internal_score);

// ---------------------------------------------------------
// UPDATE DATABASE WITH NEW SCORE
// ---------------------------------------------------------
$q2 = $db->prepare("UPDATE chapters SET plagiarism_score = ? WHERE id = ?");
$q2->bind_param('ii', $final_score, $chapter_id);
$q2->execute();
$q2->close();

$db->close();

echo json_encode(['status' => 'success', 'score' => $final_score]);
?>
