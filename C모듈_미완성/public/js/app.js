class App {
	constructor() {
		this.items = [];
		this.pageNum = 0;
		this.pageLen = 0;
		this.slideIdx = 0;
		this.slideLen = 0;
		this.pageTg = "general";
		this.listHead = `
		<ul class="head rel">
		<li>번호</li>
		<li>축제명</li>
		<li>기간</li>
		<li>장소</li>
		</ul>`;
		this.getImg = (sn, no, images) => images.map((v, idx) => `<img class="pageObj" src="http://127.0.0.1/xml/festivalImages/${sn.length == 1 ? "00"+sn : "0"+sn}_${no}/${$(v).text()}" alt="${"No Image"}">`)
		
		this.general = ({sn, no, nm, area, location, dt, cn, images}) => {
			// console.log(this.getImg(sn, no, images)[0]);
			return this.pageTg == "general" ? 
			`<div class="list rel" data-idx="${sn}">
			<div class="pageObj num rel ${sn > 30 ? 'none' : ''}">${images.length}</div>
			<div class="img-box rel pageObj">${sn > 30 ? "No Image" : this.getImg(sn, no, images)[0]}</div>
			<div class="p-box rel">
			<p><span class="pageObj">${nm}</span></p>
			<p><span class="pageObj">${dt}</span></p>
			</div>
			</div>` : 
			`<ul class="list rel" data-idx="${sn}">
			<li><span class="pageObj">${no}</span></li>
			<li><span class="pageObj">${nm}</span></li>
			<li><span class="pageObj">${dt}</span></li>
			<li class="pageObj"><span>${area}</span></li>
			</ul>`;
		}

		$.get('/xml/festivalList.xml', function(data) {
			app.items = Array.from($(data).find("item")).map(v => {
				return {
					sn:$(v).find("sn").text(),
					no:$(v).find("no").text(),
					nm:$(v).find("nm").text(),
					area:$(v).find("area").text(),
					location:Number($(v).find("sn").text()) == 34 ? $(v).find("dt").text() : $(v).find("location").text(),
					dt:Number($(v).find("sn").text()) == 34 ? $(v).find("location").text() : $(v).find("dt").text(),
					cn:$(v).find("cn").text(),
					images:[...$(v).find("images > image")]
				}	
			});
			app.pagenationCnt();
			app.items = app.items.reverse();
			app.appendLists();
			app.eve();
			app.pageMove();
		});
	}

	pagenationCnt() {
		this.pageLen = Math.ceil(app.items.length / (this.pageTg == "general" ? 6 : 10));
		$(".pagenation ul").empty();
		Array.from(Array(app.pageLen)).fill(1).forEach((v, idx) => {
			$(".pagenation ul").append(`<li class="${idx == 0 ? "active2" : ""}">${idx + 1}</li>`);
		});

		this.pageMove();
	}

	changeState(e) {
		this.pageNum = 0;
		$(".state").removeClass('active');
		this.pageTg = $(e.target).addClass('active').data("state");

		$("section.lists > div").removeClass("cover cover2").addClass(this.pageTg == "general" ? "cover" : "cover2");
		this.appendLists();
		this.pagenationCnt();
	}

	appendLists(num = 0) {
		const condition = this.pageTg == "general" ? 6 : 10;
		$("section.lists > div").empty()
		if($("section.lists > div").hasClass("cover2")) $("section.lists > div").append(this.listHead);
		this.items.filter((v, idx) => (num * condition) <= idx && (num * condition + (condition - 1)) >= idx )
		.forEach(({sn, no, nm, area, location, dt, cn, images}) => $("section.lists > div").append(this.general({sn, no, nm, area, location, dt, cn, images})));

		$(".pagenation li").removeClass('active2').eq(num).addClass('active2');
	}

	pageMove = (bool = "asd", e) =>{
		const prev = $(".pagenation .prev"), next = $(".pagenation .next");

		if(bool == "prev" && this.pageNum > 0) {
			this.appendLists(--this.pageNum)
		}else if(bool == "next" && this.pageNum < this.pageLen - 1) this.appendLists(++this.pageNum)
		else if(bool == "clk") {
			this.pageNum = $(e.target).index();
			this.appendLists(this.pageNum);
		}

		this.pageNum == 0 ? prev.css({display:"none"}) : prev.css({display:"block"});
		this.pageNum == (this.pageLen - 1) ? next.css({display:"none"}) : next.css({display:"block"});
	}

	pageObj(e) {
		$(".fesPopup").empty();
		$(".close, .fesPopup").css({display:"block"});
		this.items.filter(v => v.sn == $(e.target).parents(".list").data("idx")).forEach(({sn, no, nm, area, location, dt, cn, images}) => {
			images = this.getImg(sn, no, images);
			this.slideLen = images.length;
			$(".fesPopup").append(`
				<div class="top rel">
				<h1>${nm}</h1>
				<div class="flex rel">
				<div class="img-box rel">${images[0]}</div>
				<div class="p-box rel">
				<p>${cn}</p>
				<p><span class="high">지역:</span> <span class="area">${area}</span></p>
				<p><span class="high">장소:</span> <span class="location">${location}</span></p>
				<p><span class="high">기간:</span> <span class="dt">${dt}</span></p>
				</div></div></div>
				<h2 class="two">축제 사진</h2>
				<div class="slides rel">
				<div class="slideCover rel"></div>
				<div class="btns rel flex">
				<button class="sPrev">이전</button>
				<ul class="sBtns flex"></ul>
				<button class="sNext">다음</button>
				</div></div>
				`);
			$(".sPrev").css({display:"none"});
			$(".slideCover").css({width:100*images.length+"%"});
			images.forEach((v, idx) => $(".fesPopup .slideCover").append(`<div class="slide rel">${v}</div>`));
			images.forEach((v, idx) => $(".fesPopup ul.sBtns").append(`<li class="${idx == 0 ? "active2" : ""}"></li>`));
		});
	}

	diaMove(state = "", e) {
		if(state == "") this.slideIdx = $(e.target).index();
		else if(state == "prev") --this.slideIdx;
		else if(state == "next") ++this.slideIdx;

		
		this.slideMove(this.slideIdx);
	}

	slideMove(idx) {
		if(idx <= 0) {
			idx = 0;
			$(".sPrev").css({display:"none"});
		}else $(".sPrev").css({display:"block"});

		if(idx >= (this.slideLen - 1)) {
			idx = (this.slideLen - 1);
			$(".sNext").css({display:"none"});
		}else $(".sNext").css({display:"block"});

		$(".sBtns li").removeClass('active2').eq(this.slideIdx).addClass("active2");
		$(".fesPopup .slideCover").stop().animate({left: -100 * idx+"%"}, 500);
	}


	close(e) {
		$(".close, .popup").css({display:"none"});
	}

	eve() {
		$(document)
		.on("click", ".state", this.changeState.bind(this))
		.on("click", ".pagenation .prev", this.pageMove("prev"))
		.on("click", ".pagenation .next", this.pageMove.bind(this, "next"))
		.on("click", ".pagenation ul > li", this.pageMove.bind(this, "clk"))
		.on("click", "section.lists .pageObj", this.pageObj.bind(this))
		.on("click", ".close", this.close.bind(this))
		.on("click", ".sBtns li", this.diaMove.bind(this, ""))
		.on("click", ".sPrev", this.diaMove.bind(this, "prev"))
		.on("click", ".sNext", this.diaMove.bind(this, "next"))
	}

	makeTemplate({}){
		const div = document.createElement("div");
		div.innerHTML = `<ul>
			</ul>
		`;
		return div.firstChild();
	}
}

window.onload = function() {
	window.app = new App();
}