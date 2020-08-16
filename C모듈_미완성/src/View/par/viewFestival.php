<main class="rel viewFestival">
	<section class="viewList rel">
		<h2></h2>
		<div class="cover rel">
			<?= ss() != false ? '<a href="/par/append_list" class="add">축제 등록</a>' : ''  ?>
			<ul class="head rel">
				<li>번호</li>
				<li>축제명(사진)</li>
				<li>다운로드</li>
				<li>기간</li>
				<li>장소</li>
			</ul>
			<!-- <img src="/img/15/1"> -->
			<div class="cover2 rel">
				<?php foreach ($list as $v) { ?>
					<ul class="list rel">
						<li><a href="/par/modify/<?= $v->sn ?>"><?= $v->sn ?></a></li>
						<li><a href="/par/views/<?= $v->sn ?>"><?= $v->nm ?> <span><?= $v->len ?></span></a></li>
						<li class="flex">
							<a href="/download/tar/<?= $v->sn ?>" class="down">tar</a>
							<a href="/download/zip/<?= $v->sn ?>" class="down">zip</a>
						</li>
						<li><?= $v->dt ?></li>
						<li><?= $v->area ?></li>
					</ul>
				<?php } ?>
			</div>
		</div>
	</section>
	<section class="pagenation rel">
		<h2></h2>
		<div class="cover rel flex">
			<button class="prev pageBtns" data-state="minus"><<</button>
			<ul class="flex">
			</ul>
			<button class="next pageBtns" data-state="plus">>></button>
		</div>
	</section>
</main>