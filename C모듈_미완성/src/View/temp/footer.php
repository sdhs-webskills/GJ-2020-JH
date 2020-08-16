	</div>

	<?php if($get == "/par/viewFestival"): ?>
		<script>
			const 
			max = 11,
			page = $(".pagenation"),
			view = $(".viewList .cover2"),
			list = $(".viewList .cover2 .list").clone(),
			users = "<?= isset($_SESSION['user']) ? "admin" : ""  ?>";

			let 
			len,
			imgInput = $("[name='CheckImage']"),
			pageIdx = localStorage.getItem("num") == null ? 0 : Number(localStorage.getItem("num"));

			dis = (tg, state = "none") => $(tg).css({display:state}); 

			pageMove = (num = 0) => {
				view.empty();
				page.find("ul > li").removeClass('active2').eq(pageIdx).addClass('active2');
				Array.from(list).filter((v, idx) => idx >= (num * max) && idx <= (num * max + (max - 1)) ).forEach(v => view.append(v) );

				pageChk();
			}

			pageChk = _ => {
				if(pageIdx == 0) dis(page.find(".prev"));
				else dis(page.find(".prev"), "block");

				if(pageIdx >= (len - 1)) dis(page.find(".next"));
				else dis(page.find(".next"), "block");
			}
			eve = _ => {
				$(document)
				.on("click", ".pageM", function(e) {
					pageIdx = $(e.target).index();
					
					pageMove(pageIdx);
				})
				.on("click", ".no", function(e) {
					e.preventDefault();
				})
				.on("click", ".AppendImage", function(e) { // 수정 페이지에서 추가 버튼 클릭 시
					console.log("asd");
				})
				.on("click", ".pageBtns", e => $(e.target).data("state") == "minus" ? pageMove(--pageIdx) : pageMove(++pageIdx) )


				$(window)
				.on("beforeunload", function(e) {
					localStorage.setItem("num", pageIdx);
				})
			}

			init = _ => {
				len = Math.ceil($(".viewList .cover2 .list").length / 11);
				Array.from(Array(len)).fill(1).forEach((v, idx) => page.find(".cover ul").append(`<li class="pageM ${idx == 0 ? "active2" : ""}">${idx + 1}</li>`) );

				pageMove(pageIdx);
				pageChk();
				eve();
			}

			window.onload = init;
		</script>
	<?php endif; ?>
</body>
</html>