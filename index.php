<?php
// error_reporting(E_ALL); 
// ini_set("display_errors", 1);
include('./php/db.php');
session_start();

if (!isset($_SESSION['uid'])) {
	$uid = $_COOKIE['uid'];
	if ($uid){
	    $sql = "SELECT * FROM users WHERE uid = '".$uid."'";
	    $result = mysqli_query($conn, $sql);
	    if(!($row = mysqli_fetch_array($result))) {
			unset($_COOKIE['uid']);
			setcookie('uid', '', time() - 3600, '/');
	    } else{
	    	if($row['policy_agree']){
		        $_SESSION['uid'] = $row['uid'];
		        $_SESSION['nickname'] = $row['nickname'];
		        $_SESSION['profile'] = $row['profile'];
		    } else{
				echo("<script>window.open('./policy_agree.php', '_self');</script>");
		    }
	    }
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="Pragma" content="no-cache"/>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/index.css?ver=20220302">

		<title>비덱스 | 영상 편집 외주 플랫폼</title>
		<link rel="canonical" href="https://videx.kr"/>
		<meta name="title" content="비덱스 | 영상 편집 전문가 플랫폼">
		<meta name="description" content="수준 높은 영상이 필요하세요? 영상 편집 전문가에게 맡기세요. 간단한 자막 작업부터 화려한 특수 효과까지 11분야의 영상 편집 전문가들이 항상 대기중입니다. 재치와 창의가 필요한 순간, 비덱스.">
		<meta name="keywords" content="비덱스, 영상 편집, 커미션, 프리렌서, 외주, 전문가, 서비스, 유튜브 편집, 자막 작업, 모션그래픽, 축하 영상, 동영상, 애니메이션">

		<meta property="og:title" content="비덱스 | 영상 편집 전문가 플랫폼">
		<meta property="og:image" content="./images/page_preview.png">
		<meta property="og:url" content="//www.videx.kr/">
		<meta property="og:description" content="재치와 창의가 필요한 순간, 비덱스에서 전문가에게 맡기세요">

		<link rel="shortcut icon" href="icon_QOM_icon.ico" type="image/x-icon">
		<link rel="icon" href="icon_QOM_icon.ico" type="image/x-icon">
		<!-- Global site tag (gtag.js) - Google Analytics -->

		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-219542296-1">
		</script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'UA-219542296-1');
		</script>

		<!-- Global site tag (gtag.js) - Google Ads: 10857948554 -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=AW-10857948554"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'AW-10857948554');
		</script>

	</head>
	<body>
		<!-- Channel Plugin Scripts -->
		<script>
		  (function() {
		    var w = window;
		    if (w.ChannelIO) {
		      return (window.console.error || window.console.log || function(){})('ChannelIO script included twice.');
		    }
		    var ch = function() {
		      ch.c(arguments);
		    };
		    ch.q = [];
		    ch.c = function(args) {
		      ch.q.push(args);
		    };
		    w.ChannelIO = ch;
		    function l() {
		      if (w.ChannelIOInitialized) {
		        return;
		      }
		      w.ChannelIOInitialized = true;
		      var s = document.createElement('script');
		      s.type = 'text/javascript';
		      s.async = true;
		      s.src = 'https://cdn.channel.io/plugin/ch-plugin-web.js';
		      s.charset = 'UTF-8';
		      var x = document.getElementsByTagName('script')[0];
		      x.parentNode.insertBefore(s, x);
		    }
		    if (document.readyState === 'complete') {
		      l();
		    } else if (window.attachEvent) {
		      window.attachEvent('onload', l);
		    } else {
		      window.addEventListener('DOMContentLoaded', l, false);
		      window.addEventListener('load', l, false);
		    }
		  })();
		  ChannelIO('boot', {
		    "pluginKey": "1e442593-d8ec-4020-808b-9d7e04a23ef7"
		  });
		</script>
		<!-- End Channel Plugin -->
		<div class="alarm-panel">
			<?php
				$sql = "SELECT * FROM users_alarm where uid = '".$_SESSION['uid']."' and is_service_alarm = 0 and checked = 0";
				$result = mysqli_query($conn, $sql);
			?>
			<input type="hidden" id="alarm-count" value="<?=mysqli_num_rows($result)?>">
			<?php
				while($row = mysqli_fetch_array($result)){
			?>
			<div class="alarm-sell">
				<p class="alarm"><?=$row['alarm']?></p>
				<button type="button" onclick="removeNews($('#alarm<?=$ai?>'), <?=$row['aid']?>)" class="alarm-remove">X</button>
			</div>
			<?php
				}
			?>
		</div>
		<header>
			<div class="header-panel">
				<a class="logo-text" href="./index.php">VIDEX<h1>영상 편집 프리렌서 플랫폼</h1></a>
				<input type="hidden" id="uid" value="<?=$_SESSION['uid']?>">
				<?php if (isset($_SESSION['nickname'])) {?>
				<div class="menu-panel">
					<a class="menu-text" href="./myservice.php">내 서비스</a>
					<button class="alarm-button" onclick="alarm_menu();">알림
						<?php if (mysqli_num_rows($result)){
						?>
						<p class="alarm-count-text"></p>
						<?php
							}
						?>
					</button>
					<button class="profile-img-button" onclick="account_menu();"><img alt="프로필 사진" class="profile-img" src="./user_src/profile_img/<?=$_SESSION['profile']?>"></button>
				</div>
				<div class="account-menu">
					<a href="./message.php">메세지</a>
					<button class="profile-button" onclick="showProfile()">프로필</button>
					<a href="./php/logout.php">로그아웃</a>
				</div>
				<div class="profile-box-panel">
					<button onclick="document.querySelector('#profile-img-upload').click()" class="change-profile-img"><img alt="프로필 사진" class="profile-box-img" src="./user_src/profile_img/<?=$_SESSION['profile']?>"></button>
					<div class="profile-box">
						<form id="profile-form" action="./php/profile_update.php" method="post" enctype="multipart/form-data">
							<input type="file" onchange="uploadCheck(this, '.profile-box-img');" id="profile-img-upload" name="file">
							<input type="text" name="nickname" class="profile-box-name" value="<?=$_SESSION['nickname']?>" placeholder="여기에 입력해주세요">
						</form>
						<p class="edit-profile">※ 이미지 클릭으로 업로드. 이름 입력후 엔터로 설정</p>
					</div>
				</div>
				<?php } else{ ?>
				<div class="menu-panel-nosession">
					<a class="menu-text" href="./login.php">로그인</a>
					<a class="menu-text" href="./register.php">회원가입</a>
				</div>
				<?php } ?>
			</div>
		</header>
		<main>
			<div class="banner-panel">
				<div class="mobile-banner-panel">
					<div class="banner-margin"></div>
					<img alt="재치와 창의가 필요한 순간, 비덱스" class="banner-img" src="./images/banner4.png">
					<img alt="업계 최저 수수료 5%" class="banner-img borderOn" src="./images/banner5.png">
					<img alt="첫 거래시 수수료 면제" class="banner-img" src="./images/banner6.png">
					<img alt="재치와 창의가 필요한 순간, 비덱스" class="mobile-banner-img" src="./images/m-banner2.png">
					<img alt="업계 최저 수수료 5%" class="mobile-banner-img borderOn" src="./images/m-banner3.png">
					<img alt="첫 거래시 수수료 면제" class="mobile-banner-img" src="./images/m-banner4.png">
				</div>
			</div>
			<br>
			<br>
			<div class="category">
				<div class="title-panel">
					<p class="title-text">카테고리</p>
				</div>
				<div class="category-div category-div1">
					<a href="./category.php?category=플랫폼 영상 편집"># 플랫폼 영상 편집</a>
					<a href="./category.php?category=자막 작업"># 자막 자업</a>
					<a href="./category.php?category=인트로 / 아웃트로"># 인트로 / 아웃트로</a>
					<a href="./category.php?category=모션그래픽"># 모션그래픽</a>
					 <a href="./category.php?category=촬영 / 제작"># 촬영 / 제작</a>
				</div>
				<div class="category-div category-div2">
					<a href="./category.php?category=기념 / 축하"># 기념 / 축하</a>
					<a href="./category.php?category=VLOG / 리뷰"># VLOG / 리뷰</a>
					<a href="./category.php?category=기업 / 상품 광고"># 기업 / 상품 광고</a>
					<a href="./category.php?category=애니메이션"># 애니메이션</a>
					<a href="./category.php?category=교육 영상"># 교육 영상</a>
					<a href="./category.php?category=CG / 특수 효과"># CG / 특수 효과</a>
				</div>
			</div>

			<div class="mobile-category">
				<select onchange="search()" class="category-select" name="category" value="플랫폼 영상 편집">
				    <option value="">카테고리로 검색하기</option>
				    <option value="플랫폼 영상 편집">플랫폼 영상 편집</option>
				    <option value="자막 작업">자막 작업</option>
				    <option value="인트로 / 아웃트로">인트로 / 아웃트로</option>
				    <option value="모션그래픽">모션그래픽</option>
				    <option value="촬영 / 제작">촬영 / 제작</option>
				    <option value="기념 / 축하">기념 / 축하</option>
				    <option value="VLOG / 리뷰">VLOG / 리뷰</option>
				    <option value="기업 / 상품 광고">기업 / 상품 광고</option>
				    <option value="애니메이션">애니메이션</option>
				    <option value="교육 영상">교육 영상</option>
				    <option value="CG / 특수 효과">CG / 특수 효과</option>
				</select>
			</div>
			<?php 
			function serviceCard($row, $conn){
			?>
					<div class="service-card-a" onclick="gotoService('<?=htmlspecialchars($row['sid'])?>')">
						<div class="service-card">
							<img alt="서비스 이미지" class="service-card-img" src="./service_src/preview_img/<?=htmlspecialchars($row['preview'])?>">
							<?php
								$sql = "SELECT * FROM users where uid = '".$row['uid']."'";
								$result = mysqli_query($conn, $sql);
								$row3 = mysqli_fetch_array($result);
							?>
							<p class="name"><?=htmlspecialchars($row3['nickname'])?></p>
							<p class="service-card-title"><?=htmlspecialchars($row['title'])?></p>
							<hr>
							<div class="service-card-star-panel">
								<div class="service-card-star">
									<pre class="star"><?php for ($i=0; $i < round(($row['review_speed'] + $row['review_kind'] + $row['review_result'] + $row['review_cost_effectiveness']) / 4); $i++) { 
										echo '★ ';
									}?></pre>
									<p class="unstar">
									<?php for ($i=0; $i < 5 - round(($row['review_speed'] + $row['review_kind'] + $row['review_result'] + $row['review_cost_effectiveness']) / 4); $i++) { 
										echo '★ ';
									}?>
									</p>
									<p class="selled-count">| <?=htmlspecialchars($row['sell_count'])?>회</p>
								</div>
								<p class="price"><?=htmlspecialchars(number_format($row['price']))?>원</p>
							</div>
						</div>
					</div>
			<?php
			}

			if (isset($_SESSION['nickname'])) {

			$sql = "SELECT * FROM users_history where uid = '".$_SESSION['uid']."'";
			$historyResult = mysqli_query($conn, $sql);

			if ($row2 = mysqli_fetch_array($historyResult)){
				if ($row2['visit_history'] != null) {
			?>
			<div class="recent-panel">
				<div class="title-panel">
					<p class="title-text">최근 본 서비스</p>
				</div>
				<div class="services-panel">
					<?php

					$visit_history_array = explode('¿', $row2['visit_history']);
						for ($j=0; $j < count($visit_history_array) - 1; $j++) {
							$sql = "SELECT * FROM services where sid = '".$visit_history_array[$j]."' AND allowed = 'allow'";
							$historyServiceResult = mysqli_query($conn, $sql);

							if ($row = mysqli_fetch_array($historyServiceResult)){
								serviceCard($row, $conn);		
							} else{
				?>
					<div class="service-card-a">
						<div class="service-card">
							<img alt="비승인 서비스 이미지" class="service-card-img" src="./images/service_card.png">
							<p class="service-card-title">비승인 서비스입니다</p>
							<p class="name">정보 없음</p>
							<div class="service-card-star-panel">
								<div class="service-card-star">
									<p class="unstar">★ ★ ★ ★ ★</p>
									<p class="selled-count">| 0회</p>
									<p class="price">0 원</p>
								</div>
							</div>
						</div>
					</div>

				<?php
						}

						if (($j + 1) % 4 != 0) {
				?>
						<div class="service-card-margin"></div>
				<?php
						}
					}
				}
				$j = 0;
				?>
				</div>
			</div>
			<?php
				}
			}
			$sql = "SELECT interest FROM users where uid = '".$_SESSION['uid']."'";
			$userResult = mysqli_query($conn, $sql);

			if ($row = mysqli_fetch_array($userResult)){
				if ($row['interest'] != null) {
					$sql = "SELECT * FROM services where category = '".$row['interest']."' AND allowed = 'allow' ORDER BY review_result + review_kind + review_speed + review_cost_effectiveness DESC, create_time DESC Limit 12";
				} else{
					$sql = "SELECT * FROM services where allowed = 'allow' ORDER BY review_result + review_kind + review_speed + review_cost_effectiveness DESC, create_time DESC Limit 12";
				}
			}

			$serviceResult = mysqli_query($conn, $sql);

			if(mysqli_num_rows($serviceResult)){
			?>
			<div class="recommend-panel">
				<div class="title-panel">
					<p class="title-text">'<span style="font-weight: 500;"><?=$_SESSION['nickname']?></span>'님 관심사의 추천 서비스</p>
				</div>
				<div class="services-panel">

				<?php
				while($row = mysqli_fetch_array($serviceResult)) {
					serviceCard($row, $conn);
					if (($j++ + 1) % 4 != 0) {
				?>
					<div class="service-card-margin"></div>
				<?php
					}
				}
				$j = 0;
				?>
				</div>
			</div>
			<?php }
			
				$sql = "SELECT * FROM services where allowed = 'allow' ORDER BY review_result + review_kind + review_speed + review_cost_effectiveness DESC, create_time DESC Limit 20";
				$userResult = mysqli_query($conn, $sql);

				if(mysqli_num_rows($userResult)){
			?>
			<div class="famous-panel">
				<div class="title-panel">
					<p class="title-text">인기 서비스</p>
				</div>
				<div class="services-panel">
					<?php
						while($row = mysqli_fetch_array($userResult)){
							serviceCard($row, $conn);
							if (($j++ + 1) % 4 != 0) {
					?>
							<div class="service-card-margin"></div>
					<?php
							}
						}
						$j = 0;
					?>
				</div>
			</div>
			<?php
				}
			if (!isset($_SESSION['nickname'])) {
				$sql = "SELECT * FROM services where allowed = 'allow' ORDER BY create_time DESC Limit 20";
				$userResult = mysqli_query($conn, $sql);
				if(mysqli_num_rows($userResult)){
			?>
			<div class="new-panel">
				<div class="title-panel">
					<p class="title-text">신규 서비스</p>
				</div>
				<div class="services-panel">
					<?php
						while($row = mysqli_fetch_array($userResult)){
						serviceCard($row, $conn);
							if (($j++ + 1) % 4 != 0) {
					?>
							<div class="service-card-margin"></div>
					<?php
							}
						}
					?>
				</div>
			</div>
			<?php
				}
			}
			?>
			<footer>
				<div class="information-panel">
					<div class="footer-service-panel">
						<p class="footer-title">의뢰인 서비스</p>
						<a class="footer-text" href="">최근 의뢰 내역</a>
					</div>
					<div class="footer-service-panel">
						<p class="footer-title">전문가 서비스</p>
						<a class="footer-text" href="./sales_history.php?num=0">최근 판매 내역</a>
						<a class="footer-text" href="./calc.php">수익 계산기</a>
					</div>
					<div class="footer-service-panel">
						<p class="footer-title">고객 지원</p>
						<a class="footer-text" href="./help.php">서비스 문의</a>
						<a class="footer-text" href="">거래 중개 신청</a>
					</div>
					<div class="footer-service-panel">
						<p class="footer-title">비덱스 정보</p>
						<a class="footer-text" href="./info/introduce.html">회사 소개</a>
					</div>
				</div>
				<div class="business-panel">
					<p class="business-info-text">비덱스 | 사업자등록번호: 169-32-01046 | 대표: 장혁수 | 전화번호: 010-4623-9094 | 사업장 주소: 경기도 화성시 동탄순환대로12길 85 3633동 901호 | <a href="./videx_privacy_policy.html">개인정보 처리방침</a> | <a href="./videx_service_policy.html">서비스 이용약관</a></p>
				</div>
			</footer>
		</main>
		<div class="m-margin"></div>
		<div class="mobile-navigation-panel">
			<button class="nav-button" onclick="window.open('./index.php', '_self');" id="m-home"><img alt="홈" class="nav-img" src="./images/selected_home.svg"><p class="nav-selected-text">홈</p></button>
			<?php
				if(isset($_SESSION['uid'])){
			?>
			<button class="nav-button" onclick="window.open('./alarm.php', '_self');" id="m-alarm"><img alt="알림" class="nav-img" src="./images/alarm.svg"><p class="nav-text">알림</p></button>
			<button class="nav-button" onclick="window.open('./myservice.php', '_self');" id="m-service"><img alt="내 서비스" class="nav-img" src="./images/service.svg"><p class="nav-text">내 서비스</p></button>
			<button class="nav-button" onclick="window.open('./message.php', '_self');" id="m-service"><img alt="메세지" class="nav-img" src="./images/message.svg"><p class="nav-text">메세지</p></button>
				<button class="nav-button" onclick="showProfile()" id="m-account"><img alt="프로필" class="nav-img" src="./images/account.svg"><p class="nav-text">프로필</p></button>
			<?php
				} else{
			?>
				<button class="nav-button" onclick="window.open('./login.php', '_self');" id="m-account"><img alt="로그인" class="nav-img" src="./images/account.svg"><p class="nav-text">로그인</p></button>
				<button class="nav-button" onclick="window.open('./register.php', '_self');" id="m-account"><img alt="회원가입" class="nav-img" src="./images/account.svg"><p class="nav-text">회원가입</p></button>
			<?php
				}
			?>
		</div>

		<script src="https://code.jquery.com/jquery-latest.js"></script>
		<script src="./js/anime.min.js"></script>
		<script src="./js/index.js?ver=220225"></script>
	</body>
</html>