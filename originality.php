<?php

class OriginalityChecker {
    // List of common English stopwords to filter out
    private static $stopwords = [
        'a', 'about', 'above', 'after', 'again', 'against', 'all', 'am', 'an', 'and', 'any', 'are', 'arent', 
        'as', 'at', 'be', 'because', 'been', 'before', 'being', 'below', 'between', 'both', 'but', 'by', 
        'cant', 'cannot', 'could', 'couldnt', 'did', 'didnt', 'do', 'does', 'doesnt', 'doing', 'dont', 'down', 
        'during', 'each', 'few', 'for', 'from', 'further', 'had', 'hadnt', 'has', 'hasnt', 'have', 'havent', 
        'having', 'he', 'hed', 'hell', 'hes', 'her', 'here', 'heres', 'hers', 'herself', 'him', 'himself', 
        'his', 'how', 'hows', 'i', 'id', 'ill', 'im', 'ive', 'if', 'in', 'into', 'is', 'isnt', 'it', 'its', 
        'itself', 'lets', 'me', 'more', 'most', 'mustnt', 'my', 'myself', 'no', 'nor', 'not', 'of', 'off', 
        'on', 'once', 'only', 'or', 'other', 'ought', 'our', 'ours', 'ourselves', 'out', 'over', 'own', 'same', 
        'shant', 'she', 'shed', 'shell', 'shes', 'should', 'shouldnt', 'so', 'some', 'such', 'than', 'that', 
        'thats', 'the', 'their', 'theirs', 'them', 'themselves', 'then', 'there', 'theres', 'these', 'they', 
        'theyd', 'theyll', 'theyre', 'theyve', 'this', 'those', 'through', 'to', 'too', 'under', 'until', 'up', 
        'very', 'was', 'wasnt', 'we', 'wed', 'well', 'were', 'weve', 'werent', 'what', 'whats', 'when', 'whens', 
        'where', 'wheres', 'which', 'while', 'who', 'whos', 'whom', 'why', 'whys', 'with', 'wont', 'would', 
        'wouldnt', 'you', 'youd', 'youll', 'youre', 'youve', 'your', 'yours', 'yourself', 'yourselves'
    ];

    /**
     * Tokenizes a text into words, removing punctuation and stopwords.
     */
    public static function tokenize($text) {
        $text = strtolower($text);
        // Remove punctuation and non-alphanumeric characters
        $text = preg_replace('/[^a-z0-9\s]/', '', $text);
        // Split by whitespace
        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        // Filter stopwords and short words
        $filteredWords = [];
        foreach ($words as $word) {
            if (strlen($word) >= 3 && !in_array($word, self::$stopwords)) {
                $filteredWords[] = $word;
            }
        }
        return $filteredWords;
    }

    /**
     * Computes TF-IDF Cosine Similarity between a query and a set of candidate documents.
     * @param string $queryText
     * @param array $candidates Associative array of [id => content]
     * @return array Associative array of [id => score_percentage]
     */
    public static function computeTfidfSimilarities($queryText, $candidates) {
        $allDocs = [];
        $allDocs['query'] = self::tokenize($queryText);
        
        foreach ($candidates as $id => $content) {
            $allDocs[$id] = self::tokenize($content);
        }

        $nDocs = count($allDocs);
        if ($nDocs <= 1) {
            return [];
        }

        // Calculate Document Frequency (DF) for each word
        $df = [];
        foreach ($allDocs as $docName => $words) {
            $uniqueWords = array_unique($words);
            foreach ($uniqueWords as $word) {
                if (!isset($df[$word])) {
                    $df[$word] = 0;
                }
                $df[$word]++;
            }
        }

        // Calculate IDF for each word using smooth formula: log((1 + N) / (1 + DF)) + 1
        $idf = [];
        foreach ($df as $word => $count) {
            $idf[$word] = log((1 + $nDocs) / (1 + $count)) + 1;
        }

        // Calculate TF-IDF vectors
        $vectors = [];
        foreach ($allDocs as $docName => $words) {
            $wordCounts = array_count_values($words);
            $totalWords = count($words);
            
            $vector = [];
            foreach ($wordCounts as $word => $count) {
                $tf = $count / ($totalWords ?: 1);
                $vector[$word] = $tf * $idf[$word];
            }
            $vectors[$docName] = $vector;
        }

        // Calculate Cosine Similarity of query vector against candidate vectors
        $queryVector = $vectors['query'];
        
        // Compute query norm
        $queryNorm = 0;
        foreach ($queryVector as $val) {
            $queryNorm += $val * $val;
        }
        $queryNorm = sqrt($queryNorm);

        $results = [];
        foreach ($candidates as $id => $content) {
            $candidateVector = $vectors[$id];
            
            // Compute candidate norm
            $candidateNorm = 0;
            foreach ($candidateVector as $val) {
                $candidateNorm += $val * $val;
            }
            $candidateNorm = sqrt($candidateNorm);

            // Compute dot product
            $dotProduct = 0;
            foreach ($queryVector as $word => $val) {
                if (isset($candidateVector[$word])) {
                    $dotProduct += $val * $candidateVector[$word];
                }
            }

            // Cosine Similarity
            $denominator = $queryNorm * $candidateNorm;
            if ($denominator > 0) {
                $similarity = $dotProduct / $denominator;
            } else {
                $similarity = 0;
            }

            // Convert to percentage (0 - 100)
            $results[$id] = round($similarity * 100, 2);
        }

        return $results;
    }

    /**
     * Computes SBERT Semantic Cosine Similarity between a query and a set of candidate documents.
     * @param string $queryText
     * @param array $candidates Associative array of [id => content]
     * @return array Associative array of [id => score_percentage]
     */
    public static function computeSbertSimilarities($queryText, $candidates) {
        $input = [
            'query' => $queryText,
            'candidates' => []
        ];
        foreach ($candidates as $id => $content) {
            $input['candidates'][] = [
                'id' => $id,
                'text' => $content
            ];
        }
        
        $descriptors = [
            0 => ["pipe", "r"], // stdin
            1 => ["pipe", "w"], // stdout
            2 => ["pipe", "w"]  // stderr
        ];
        
        $process = proc_open("python3 " . __DIR__ . "/sbert_similarity.py", $descriptors, $pipes);
        if (is_resource($process)) {
            fwrite($pipes[0], json_encode($input));
            fclose($pipes[0]);
            
            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            
            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[2]);
            
            $retval = proc_close($process);
            if ($retval === 0) {
                $results = json_decode($output, true);
                $scores = [];
                if (is_array($results)) {
                    foreach ($results as $res) {
                        $scores[$res['id']] = $res['score'];
                    }
                }
                return $scores;
            }
        }
        return [];
    }
}
