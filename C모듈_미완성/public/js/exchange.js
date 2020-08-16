class Exchange {
	data;
	constructor() {
		this.scrolls = localStorage.getItem("sclT") === "null" ? 0 : Number(localStorage.getItem("sclT"));
		// console.log(this.scrolls);
		this.bool = localStorage.getItem("state") == null ? "false" : String(localStorage.getItem("state"));
		this.ulList = ({result, cur_unit, ttb, tts, deal_bas_r, bkpr, yy_efee_r, ten_dd_efee_r, kftc_bkpr, kftc_deal_bas_r, cur_nm}) => 
		`<ul class="rel">
		<li>${result}</li>
		<li>${cur_unit}</li>
		<li>${ttb}</li>
		<li>${tts}</li>
		<li>${deal_bas_r}</li>
		<li>${bkpr}</li>
		<li>${yy_efee_r}</li>
		<li>${ten_dd_efee_r}</li>
		<li>${kftc_bkpr}</li>
		<li>${kftc_deal_bas_r}</li>
		<li>${cur_nm}</li>
		</ul> `;

		$(window).scrollTop(this.scrolls);
		this.btnChk();

		fetch("/restAPI/currentExchangeRate.php")
		.then(file => file.json())
		.then(data => this.init(data));
	}

	init(data) {
		this.data = data;
		if(this.data.statusCd != 200) {
			$(".ulCover,.head2,.ulCover").css({visibility:"hidden"});
			$("main").prepend(`
				<h1 class='err ab'>${this.data.statusMsg}</h1>
				<p class="ab" style="top:20px">기준일: ${this.data.dt}</p>
				<p class="ab">모든 항목수: ${this.data.totalCnt}개</p> `);
			$(window).scrollTop(this.scrolls);
		}else {
			this.btnChk();
			this.create();
		}

		this.event();
	}

	create() {
		eval(`this.data.items${this.bool == "true" ? '' : '.filter((v,idx) => idx <= 9)'}.forEach(v => $(".ulCover").append(this.ulList(v)) )`);
	}

	btnChk() {
		// if(this.bool == "true") $(".exMore").css({display:"none"});
		// else $(".exMore").css({display:"block"});
	}

	winScroll(e) {
		this.scrolls = $(window).scrollTop();
		if(this.scrolls >= ($(document).height()-$(window).height())) {
			this.bool = "true";
			$(".ulCover").empty();
			this.data.items.forEach(v => $(".ulCover").append(this.ulList(v)) );
		}
		this.btnChk();
		// this.saveData();
	}

	saveData(state = '') {
		localStorage.setItem("state", state == 'err' ? null : this.bool);
		localStorage.setItem("sclT", state == 'err' ? null : this.scrolls);
	}

	Click(e) {
		if(this.bool != "true") {
			this.bool = "true";
			this.create();
			this.btnChk();
		}
	}

	async Click2(e) {
		let t = 0;
		let test = setInterval(_ =>{++t}, 1000);
		const text = await fetch('/location.php').then(res => res.text());

		if(t == 0) {
			$(".findDia").empty().css({display:"block"}).html(text);
		}else {
			alert("찾아오시는 길을 표시할 수 없습니다.");
		}

		clearInterval(test);
		
	}

	event() {
		$(window)
			.on("beforeunload", () => exchanges.saveData())
			.on("scroll", () => this.winScroll());
		
		$(document)
			.on("click", ".exMore", () => this.Click())
			.on("click", ".find", () => this.Click2());
	}
}

const exchanges = new Exchange();
