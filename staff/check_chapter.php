<?php
  session_start();
  function query_filter($query)
  {
    $key_words = explode(' ',$query);
    $selected_words = array();

    for ($i=0; $i < count($key_words) ; $i++) {
      if (strlen($key_words[$i]) < 4) {
        continue;
      } else {
        array_push($selected_words, $key_words[$i]);
      }
    }
    return $selected_words;
  }

  function search_filter($db, $words)
  {
    $results = array(array());
    $approved = 1;
    $q = $db->prepare("select distinct id, topic, abstract_content, abstract, hits, username from projects where topic like ? && approved = ? order by hits desc");
    $x = 0;
    for ($i=0; $i < count($words); $i++) {
        $word = "%".$words[$i]."%";

        $q->bind_param('ss',$word,$approved);
        $q->execute();
        $q->bind_result($topic_id, $topic, $abstract, $file, $hit, $user);
        while ($q->fetch()) {
          $test = false;
          if ($x != 0) {
            for ($i=0; $i < $x; $i++) {
              if ($topic == $results[$i]['topic']) {
                $test = true;
              }
            }
          }

          if (!$test) {
            $results[$x]['topic'] = $topic;
            $results[$x]['abstract'] = $abstract;
            $results[$x]['topic_id'] = $topic_id;
            $results[$x]['hits'] = $hit;
            $results[$x]['file'] = $file;
            $results[$x]['user'] = $user;
          }
          $x++;
        }
        $q->free_result();
    }
    $q->close();

    return $results;
  }
?>
<?php if (isset($_SESSION['stapass'])): ?>
  <?php if (isset($_GET['id']) && !empty($_GET['id'])):
  	include '../db.php';
  	$approved = "1";
  	$chapterId = $_GET['id'];
    $q = $db->prepare("select content from chapters where id = ?");
    $q->bind_param('s', $chapterId);
    $q->execute();
    $q->bind_result($chapterContent);
    $q->fetch();
    $q->close();

    $bc = str_split($chapterContent, 150);

    $end = count($bc)-1;
    $mid = intdiv($end,2);
  	$qr1 = "%".$bc[0]."%";
    $qr2 = "%".$bc[$mid]."%";
    $qr3 = "%".$bc[$end]."%";

  	$q = $db->prepare("select distinct id, project_id, chapter, content from chapters where (content like ?  || content like ? || content like ?) && id != ? ");
  	$q->bind_param('ssss',$qr1,$qr2,$qr3,$chapterId);
  	$q->execute();
  	$q->store_result();

  	$n = $q->num_rows;
  ?>
  <!DOCTYPE HTML>
  <html lang="en">
  <head>
  <meta charset="utf-8">
  <title>Project Management System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- css -->
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/font-awesome.css">
  <!-- skin color -->
  <link href="../color/default.css" rel="stylesheet">
  <!-- Favicon -->
  <link rel="shortcut icon" href="img/favicon.ico">
  
  <link rel="stylesheet" href="/pms/css/modern-theme.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa; } .spacer { padding-top: 80px; padding-bottom: 40px; }</style>
</head>
  <body>
  <!-- navbar -->
  <div class="navbar-wrapper">
  	<div class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  		<div class="container">
  			<div class="container">
  				<div class="navbar-header">
  		      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
  		        <span class="navbar-toggler-icon"></span>
  		      </button>
  					<h3 class="navbar-brand fw-bold" style=""><a href="#">Project Management System</a></h3>
  		    </div>
  				<!-- Responsive navbar -->

  				<!-- navigation -->
          <div class="collapse navbar-collapse" id="myNavbar">
            <ul id="menu-main" class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li><a href="index.php"><span class="fa fa-home"></span> Home</a></li>
              <li><a href="logout.php"><span class="fa fa-lock"></span> Logout</a></li>
            </ul>
          </div>
  			</div>
  		</div>
  	</div>
  </div>

  <section class="mtb30 spacer">
  <div class="container">
  	<div class="row">
  		<div class="col-md-10 alignleft">
  			<?php if ($n < 1): ?>
  				<h3>No corresponding chapters were found.</h3>
  			<?php else:
          $candidates = [];
          $candidateContents = [];
          $q->bind_result($c_id, $p_id, $chapter, $content);
          while($q->fetch()) {
              $candidates[$c_id] = [
                  'id' => $c_id,
                  'project_id' => $p_id,
                  'chapter' => $chapter,
                  'content' => $content
              ];
              $candidateContents[$c_id] = $content;
          }
          $q->close();

          include_once '../originality.php';
          $lexicalScores = OriginalityChecker::computeTfidfSimilarities($chapterContent, $candidateContents);
          $semanticScores = OriginalityChecker::computeSbertSimilarities($chapterContent, $candidateContents);

          $q0 = $db->prepare("select username, topic, abstract, hits from projects where id = ?");
          $q2 = $db->prepare("select name, session from users where username = ?");

          foreach ($candidates as $c_id => &$candidate) {
              $p_id = $candidate['project_id'];
              
              $q0->bind_param('i', $p_id);
              $q0->execute();
              $q0->store_result();
              $q0->bind_result($user, $topic, $file, $hit);
              $q0->fetch();
              $q0->free_result();

              if ($user != "admin") {
                  $q2->bind_param('s', $user);
                  $q2->execute();
                  $q2->store_result();
                  $q2->bind_result($name, $session);
                  $q2->fetch();
                  $q2->free_result();
              } else {
                  $name = "Administrator";
                  $session = "";
              }

              $lexScore = isset($lexicalScores[$c_id]) ? $lexicalScores[$c_id] : 0;
              $semScore = isset($semanticScores[$c_id]) ? $semanticScores[$c_id] : 0;
              $hybridScore = round(($lexScore + $semScore) / 2, 2);

              $candidate['user'] = $user;
              $candidate['topic'] = $topic;
              $candidate['file'] = $file;
              $candidate['hit'] = $hit;
              $candidate['student_name'] = $name;
              $candidate['session'] = $session;
              $candidate['lexical_score'] = $lexScore;
              $candidate['semantic_score'] = $semScore;
              $candidate['hybrid_score'] = $hybridScore;
          }
          unset($candidate);
          $q0->close();
          $q2->close();
          $db->close();

          uasort($candidates, function($a, $b) {
              return $b['hybrid_score'] <=> $a['hybrid_score'];
          });
        ?>
          <h3 class="mb-4">Internal Plagiarism Matches</h3>
  				<?php foreach($candidates as $cand):
            $lex = $cand['lexical_score'];
            $sem = $cand['semantic_score'];
            $hyb = $cand['hybrid_score'];

            $badgeClass = 'bg-success';
            if ($hyb >= 40) $badgeClass = 'bg-danger';
            elseif ($hyb >= 15) $badgeClass = 'bg-warning text-dark';
          ?>
  					<a href="../hits.php?id=<?php echo $cand['project_id']; ?>&file=<?php echo urlencode($cand['file']); ?>&hit=<?php echo $cand['hit']; ?>&type=chapter" target="_blank" style="text-decoration:none; color:inherit;">
  						<div class="glass-card mb-3 p-3 position-relative" style="border-left: 5px solid <?php echo ($hyb >= 40) ? '#ef4444' : (($hyb >= 15) ? '#f59e0b' : '#10b981'); ?>">
  							<h4 class="mb-1" style="color: #0f172a; font-size:1.15rem; font-weight:600;"> 
                  <?php echo htmlspecialchars($cand['topic']); ?> (CHAPTER <?php echo htmlspecialchars($cand['chapter']); ?>) 
                </h4>
                <div class="mb-2 text-muted" style="font-size: 0.85rem;">
                  <i class="fa-solid fa-user me-1"></i> <?php echo htmlspecialchars($cand['student_name'])." (".$cand['session'].")"; ?>
                </div>
  							<div class="abstract text-secondary mb-3" style="font-size:0.9rem; line-height: 1.5;">
  								<?php echo htmlspecialchars(substr($cand['content'], 0, 350)) . '...'; ?>
  							</div>
                <div class="d-flex flex-wrap gap-3 mt-2" style="font-size: 0.85rem;">
                  <span class="badge bg-light text-dark border">
                    Lexical (TF-IDF): <strong><?php echo $lex; ?>%</strong>
                  </span>
                  <span class="badge bg-light text-dark border">
                    Semantic (SBERT): <strong><?php echo $sem; ?>%</strong>
                  </span>
                  <span class="badge <?php echo $badgeClass; ?> px-2 py-1">
                    Hybrid Match: <strong><?php echo $hyb; ?>%</strong>
                  </span>
                </div>
  						</div>
  					</a>
  				<?php endforeach; ?>
          <?php endif; ?>

  		</div>
  		<div class="col-md-2"></div>
  	</div>
  </div>
  </section>

  <footer>
  <div class="container">
  	<div class="row">
  		<div class="col-md-6 offset-md-3">
  			<p class="copyright">
  				&copy; <?php echo date('Y'); ?>. All rights reserved.
  			</p>
  		</div>
  	</div>
  </div>
  <!-- ./container -->
  </footer>
  <!--<a href="#" class="scrollup"><i class="icon-angle-up icon-square icon-bgdark icon-2x"></i></a>-->
  <!-- jQuery -->
  <script src="js/jquery.js"></script>
  <script src="js/jquery.localscroll-1.2.7-min.js"></script>
  <!-- bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- custom functions -->
  <script src="js/custom.js"></script>
  </body>
  </html>
  <?php else:
  	header("Location:index.php");
  	exit;
  ?>
  <?php endif; ?>
<?php else:
  header("Location:logout.php");
  exit;
?>
<?php endif; ?>
