<?php if (isset($_GET['q']) && !empty($_GET['q'])):
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

  function search_filter($db, $words){
		$results = array(array());
    $ids = array();
    $approved = 1;
    $q = $db->prepare("select distinct id, topic, abstract_content, abstract, hits, username from projects where topic like ? && approved = ? order by hits desc");
    $x = 0;
    for ($i=0; $i < count($words); $i++) {
        $word = "%".$words[$i]."%";

        $q->bind_param('ss',$word,$approved);
        $q->execute();
        $q->bind_result($topic_id, $topic, $abstract, $file, $hit, $user);

        while ($q->fetch()) {
          if (array_search($topic_id, $ids) === false) {
            array_push($ids, $topic_id);
            $results[$x]['topic'] = $topic;
            $results[$x]['abstract'] = $abstract;
            $results[$x]['topic_id'] = $topic_id;
            $results[$x]['hits'] = $hit;
            $results[$x]['file'] = $file;
            $results[$x]['user'] = $user;
						$x++;
          }
        }
        $q->free_result();
    }
    $q->close();

    //$results = array_unique($results);
    return $results;
  }

	include 'db.php';
	$approved = "1";
	$qw = $_GET['q'];
	$query = "%".$qw."%";

	$q0 = $db->prepare("select id, topic, abstract_content, abstract, hits, username from projects where abstract_content like ? && approved = ? order by hits desc");
	$q0->bind_param('ss',$query,$approved);
	$q0->execute();
	$q0->store_result();

	$filtered_query = query_filter($query);
	$results = search_filter($db, $filtered_query);

	//var_dump($results);
	//exit;
	if (count($results[0]) > 0) {
		$n = $q0->num_rows + count($results);
	} else {
		$n = $q0->num_rows;
	}
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
<link href="css/style.css" rel="stylesheet">
<link rel="stylesheet" href="css/font-awesome.css">
<!-- skin color -->
<link href="color/default.css" rel="stylesheet">
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
		        <li><a href="#"><span class="fa fa-book"></span> About</a></li>
		        <li><a href="login.php"><span class="fa fa-lock"></span> Login</a></li>
		      </ul>
		    </div>
			</div>
		</div>
	</div>
</div>



<section class="mtb30">
<div class="container">
	<div class="row">
		<div class="col-12 alignleft">
			<form class="" action="search-result.php" method="get">
        <div class="row">
          <div class="col-md-7">
            <div class="form-group">
    					<input type="text" name="q" value="<?php echo $qw; ?>" class="form-control alignleft" placeholder="Enter project topic/ research area" style="height:100%; padding:12px; border-radius:0px; width:justify">
    				</div>
          </div>
            <div class="col-md-5">
              <div class="form-group">
                <button type="submit" name="button" class="btn btn-primary"> <span class="fa fa-search"></span> </button>
              </div>
            </div>
          </div>
			</form>
		</div>
	</div>
</div>
</section>

<section class="mtb30">
<div class="container">
	<div class="row">
		<div class="col-md-10 alignleft">
			<?php if ($n < 1): ?>
				<h3>Your search did not return any result.</h3>
			<?php else:
				$q0->bind_result($topic_id, $topic, $abstract, $file, $hit, $user);
				$ids = array();
			?>

				<?php while($q0->fetch()):
					array_push($ids, $topic_id);

					if ($user != "admin") {
						$q2 = $db->prepare("select name, session from users where username = ?");
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
							<h4 style="margin-bottom: 2px;"> <?php echo $topic; ?> <i><small><?php echo $name." ".$session; ?></small></i></h4>
						</div>
						<div class="abstract">
							<p style="font-size:12px"> <?php $abstract = substr($abstract,0,390)."..."; echo $abstract;  ?> </p>
						</div>
					</a>
				<?php endwhile; $q0->free_result(); ?>

				<?php if (count($results[0]) > 0): ?>
					<?php for($i = 0; $i < count($results); $i++): ?>

					<?php if (array_search($results[$i]['topic_id'], $ids) === false):

						if ($results[$i]['user'] != "admin") {
							$q2 = $db->prepare("select name, session from users where username = ?");
							$q2->bind_param('s',$results[$i]['user']);
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
					<a href="hits.php?id=<?php echo $results[$i]['topic_id']; ?>&file=<?php echo $results[$i]['file']; ?>&hit=<?php echo $results[$i]['hits']; ?>" target="_blank">
						<div class="topic">
							<h4 style="margin-bottom: 2px;"> <?php echo $results[$i]['topic']; ?> <i> <small><?php echo $name." ".$session; ?></small> </i></h4>
						</div>
						<div class="abstract">
							<p style="font-size:12px"> <?php $abstract = substr($results[$i]['abstract'],0,390)."..."; echo $abstract;  ?> </p>
						</div>
					</a>
					<?php endif; ?>

					<?php endfor;?>

				<?php endif; ?>

			<?php endif; ?>
			<?php $q0->close();  $db->close(); ?>
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
