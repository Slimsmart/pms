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
                true;
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

    //$results = array_unique($results);
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
    //$filtered_query = $query;
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
  <link href="../css/bootstrap-responsive.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/bootstrap.css">
  <link href="../css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/font-awesome.css">
  <!-- skin color -->
  <link href="../color/default.css" rel="stylesheet">
  <!-- Favicon -->
  <link rel="shortcut icon" href="img/favicon.ico">
  </head>
  <body>
  <!-- navbar -->
  <div class="navbar-wrapper">
  	<div class="navbar navbar-inverse">
  		<div class="navbar-inner">
  			<div class="container">
  				<div class="navbar-header">
  		      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
  		        <span class="icon-bar"></span>
  		        <span class="icon-bar"></span>
  		        <span class="icon-bar"></span>
  		      </button>
  					<h3 class="brand navbar-brand" style=""><a href="#">Project Management System</a></h3>
  		    </div>
  				<!-- Responsive navbar -->

  				<!-- navigation -->
          <div class="collapse navbar-collapse" id="myNavbar">
            <ul id="menu-main" class="nav navbar-nav navbar-right">
              <li><a href="index.php"><span class="fa fa-home"></span> Home</a></li>
              <li><a href="logout.php"><span class="fa fa-lock"></span> Logout</a></li>
            </ul>
          </div>
  			</div>
  		</div>
  	</div>
  </div>

  <section class="mtb30">
  <div class="container">
  	<div class="row">
  		<div class="span10 alignleft">
  			<?php if ($n < 1): ?>
  				<h3>No corresponding chapters were found.</h3>
  			<?php else: $q->bind_result($c_id, $p_id, $chapter, $content);
          $q0 = $db->prepare("select username, topic, abstract from projects where id = ?");
          $q2 = $db->prepare("select name, session from users where username = ?");
        ?>
  				<?php while($q->fetch()):
            $q0->bind_param('s',$p_id);
            $q0->execute();
            $q0->store_result();
            $q0->bind_result($user, $topic, $file);
            $q0->fetch();

            if ($user != "admin") {
              $q2->bind_param('s',$user);
              $q2->execute();
              $q2->bind_result($name, $session);
              $q2->fetch();
              $q2->close();
            }
            else {
              $name = "Administrator";
              $session = "";
            }
          ?>
  					<a href="hits.php?id=<?php echo $topic_id; ?>&file=<?php echo $file; ?>&hit=<?php echo $hit; ?>" target="_blank">
  						<div class="topic">
  							<h4 style="margin-bottom: 2px;"> <?php echo $topic; ?> (CHAPTER <?php echo $chapter; ?>) <i><small><?php echo $name." ".$session; ?></small></i> </h4>
  						</div>
  						<div class="abstract">
  							<p style="font-size:12px"> <?php $chapterContent = substr($content,0,390)."..."; echo $chapterContent;  ?> </p>
  						</div>
  					</a>

  				<?php endwhile; $q->free_result(); $q0->close(); $q->close(); $db->close(); ?>
          <?php endif; ?>

  		</div>
  		<div class="span2"></div>
  	</div>
  </div>
  </section>

  <footer>
  <div class="container">
  	<div class="row">
  		<div class="span6 offset3">
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
  <script src="js/bootstrap.js"></script>
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
