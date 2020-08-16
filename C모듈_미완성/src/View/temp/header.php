<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width">
	<title>전국대회(광주)</title>
	<link rel="stylesheet" href="/css/bootstrap.css">
	<link rel="stylesheet" href="/css/bootstrap.min.css">
	<link rel="stylesheet" href="/css/style.css">
	<script src="/js/jquery-3.5.0.min.js"></script>
</head>
<body>
	<div class="wrap rel">
		<header class="flex">
			<div class="logo rel">
				<a href="/">전축 축제 On!</a>
			</div>
			<nav class="menu rel">
				<ul class="flex rel">
					<?php if(!ss()): ?>
						<li><a href="/user/login">로그인</a></li>
						<li><a href="/user/user">회원가입</a></li>
						<?php else: ?>
							<li><a href="/user/logout">로그아웃</a></li>
						<?php endif; ?>
						<li><a href="/">Home</a></li>
						<li><a href="/fes.html">전북 대표 축제</a></li>
						<li><a href="/par/viewFestival">축제 정보</a></li>
						<li><a href="/par/calendar">축제 일정</a></li>
						<li><a href="/exchange.html">환율안내</a></li>
						<li class="last"><a href="#">종합지원센터</a>
							<ul class="rel">
								<li><a href="#">-공지사항</a></li>
								<li><a href="#">-센터소개</a></li>
								<li><a href="#">-관광정보 문의</a></li>
								<li><a href="#">-공공 데이터 개방</a></li>
								<li><a href="#">-찾아오시는 길</a></li>
							</ul>
						</li>
					</ul>
				</nav>
				<div class="sear rel">
					<input type="text" name="search" placeholder="검색어를 입력해 주세요.">
					<button>검색</button>
				</div>
			</header>