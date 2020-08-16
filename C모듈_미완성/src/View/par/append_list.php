<main class="rel appendList reg">
	<section class="regPage rel">
		<h2></h2>
		<div class="cover rel">
			<h2>축제 등록</h2>
			<form action="/par/modify_action" method="post" enctype="multipart/form-data" class="gogo">
				<input type="hidden" name="delImgs">
				<input type="hidden" name="sn">
				<div class="box rel">
					<p>축제명</p>
					<input type="text" name="names" placeholder="Write names...">
				</div>
				<div class="box flex cuts rel">
					<div>
						<p>지역</p>
						<input type="text" name="area" placeholder="Write your area...">
					</div>
					<div>
						<p>기간</p>
						<input type="text" name="dt" placeholder="Write your Date(yyyy-MM-dd ~ yyyy-MM-dd)">
					</div>
				</div>
				<div class="box  rel">
					<p>장소</p>
					<input class="loca" type="text" name="location" placeholder="Write your location...">
				</div>
				<div class="box rel">
					<p>축제 사진</p>
					<div class="flex rel">
						<input type="file" name="appendImgs[]" id="appendImgs" multiple>
						<!-- <button type="button" class="AppendImage">추가</button> -->
					</div>
				</div>
			</form>
			<div class="flex close-btns rel">
				<button type="button" onclick="location.replace('/par/viewFestival');">닫기</button>
				<button type="button" class="save-btn">저장</button>
			</div>
		</div>
	</section>
</main>

<script>
	let 
	test,
	filesArr,
	arr = [],
	imgInput = document.getElementById("CheckImage");
	eve = _ => {
		$(document)
		.on("click", ".no", function(e) {
			e.preventDefault();
		})
		.on("click", ".AppendImage", function(e) { // 수정 페이지에서 추가 버튼 클릭 시
			$(".img-cover .none").removeClass('none');
		})
		.on("click", ".img-remove", function(e) { // X 버튼 클릭 시
			$(e.target).parents(".img-box").remove();
		})
		.on("click", ".save-btn", function(e) { // 저장 버튼 클릭 시
			// 2019-05-03 ~ 2019-05-03
			let 
			arr = [],
			tg = $("[name='chk']"),
			len = tg.length,
			reg = /[0-9]{4}-(0?[1-9]|1[012])-(0?[1-9]|[12][0-9]|3[01]) ~ [0-9]{4}-(0?[1-9]|1[012])-(0?[1-9]|[12][0-9]|3[01])/;

			Array(len).fill(1).forEach((v, idx) => { if($(tg.eq(idx)).is(":checked")) arr.push($(tg.eq(idx)).siblings('p').text()); });

			$("[name='delImgs']").val(arr);

			let dates = $("[name='dt']").val();
			if(!reg.test(dates) ) return alert("날짜는 yyyy-MM-dd ~ yyyy-MM-dd 형식 입니다.");

			dates = dates.trim().split("~");
			dates = [new Date(dates[0]), new Date(dates[1])];

			if( dates[0].getTime() > dates[1].getTime() ) return alert("끝나는 날짜가 시작 날짜보다 큽니다.");

			$(".gogo").submit();
		})

	}

	window.onload = eve;
</script>