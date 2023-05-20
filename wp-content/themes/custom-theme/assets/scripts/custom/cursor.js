jQuery(document).ready(function(){
	window.mobileAndTabletcheck = function() {
		var check = false;
		(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
		return check;
	};

	let clientX = -100;
	let clientY = -100;
	const innerCursor = document.querySelector(".cursor--small");
	const outerCursor = document.querySelector(".cursor--big");

	let lastX = 0;
	let lastY = 0;
	let isStuck = false;
	let showCursor = false;
	let group, stuckX, stuckY, fillOuterCursor;

	const initCursor = () => {
		const lerp = (a, b, n) => {
			return (1 - n) * a + n * b;
		};
		document.addEventListener("mousemove", e => {
			clientX = e.clientX;
			clientY = e.clientY;
		});
		const render = () => {
			if (!isStuck) {
				lastX = lerp(lastX, clientX, 0.2);//0.2
				lastY = lerp(lastY, clientY, 0.2);
				outerCursor.style.transform = `translate(${lastX}px, ${lastY}px)`;
			} else if (isStuck) {
				lastX = lerp(lastX, stuckX, 0.2);
				lastY = lerp(lastY, stuckY, 0.2);
				outerCursor.style.transform = `translate(${lastX}px, ${lastY}px)`;
			}
			innerCursor.style.transform = `translate(${clientX}px, ${clientY}px)`;
			requestAnimationFrame(render);
		};
		requestAnimationFrame(render);
	};

	const initHovers = () => {
		//const canvas = document.querySelector(".cursor--canvas");
		const canvas = document.querySelector(".cursor--big");
		const handleMouseEnter = e => {
			const navItem = e.currentTarget;
			const navItemBox = navItem.getBoundingClientRect();
			stuckX = Math.round(navItemBox.left + navItemBox.width / 2);
			stuckY = Math.round(navItemBox.top + navItemBox.height / 2);
			jQuery(innerCursor).addClass("enterMagnet");
			jQuery(canvas).addClass("cursor--big-magnet");
			isStuck = true;
		};
		const handleMouseLeave = () => {
			isStuck = false;
			jQuery(innerCursor).removeClass("enterMagnet");
			jQuery(canvas).removeClass("cursor--big-magnet");
		};
		const handleMouseEnterLinks = e => {
			jQuery(innerCursor).addClass("enterLinks");
			jQuery(canvas).addClass("hidden");
		};
		const handleMouseLeaveLinks = () => {
			jQuery(innerCursor).removeClass("enterLinks");
			jQuery(canvas).removeClass("hidden");
		};
		const handleMouseEnterCloses = e => {
			if(jQuery(e.target).hasClass("modal fade") || jQuery(e.target).attr("id") ==="overlay-side-panel-right" || jQuery(e.target).attr("id") == "overlay-side-panel-left"){
				jQuery(innerCursor).addClass("enterClose");
				jQuery(canvas).addClass("hidden");
			}
			if(jQuery(".modal-dialog").attr('listener') !== 'true') {
				jQuery(".modal-dialog").on("mouseover", handleMouseLeaveCloses);
				jQuery(".modal-dialog").attr('listener',true);
			}
		};
		const handleMouseLeaveCloses = e => {
			jQuery(innerCursor).removeClass("enterClose");
			jQuery(canvas).removeClass("hidden");
		};
		const handleMouseEnterDraggable = e => {
			jQuery(innerCursor).addClass("enterDraggable");
			jQuery(canvas).addClass("hidden");
		};
		const handleMouseLeaveDraggable = e => {
			jQuery(innerCursor).removeClass("enterDraggable");
			jQuery(canvas).removeClass("hidden");
		};
		const handleMouseDragDraggable = e => {
			clientX = e.clientX;
			clientY = e.clientY;
		};
		const menuButtons = document.querySelectorAll(".menu-button, .os-nav, .modal-header .close, .side-panel-close, .vm_remove_product, .vmicon, .sitemessage_close");
		menuButtons.forEach(item => {
			if(item.getAttribute('listener') !== 'true' || jQuery(item).hasClass("vm_remove_product")) {
				item.addEventListener("mousemove", handleMouseEnter);
				item.addEventListener("mouseleave", handleMouseLeave);
				item.addEventListener("click", handleMouseLeave);
				item.setAttribute('listener', 'true');
			}
		});
		const links = document.querySelectorAll("a, .cursor-links, .btn, .swiper-pagination, button");
		links.forEach(item => {
			if(item.getAttribute('listener') !== 'true') {
				item.addEventListener("mouseenter", handleMouseEnterLinks);
				item.addEventListener("mouseleave", handleMouseLeaveLinks);
				item.setAttribute('listener', 'true');
			}
		});
		const closes = document.querySelectorAll("#overlay-side-panel-left, #overlay-side-panel-right, .modal.fade");
		closes.forEach(item => {
			if(item.getAttribute('listener') !== 'true') {
				item.addEventListener("mouseover", handleMouseEnterCloses);
				item.addEventListener("mouseout", handleMouseLeaveCloses);
				item.addEventListener("click", handleMouseLeaveCloses);
				item.setAttribute('listener', 'true');
			}
		});
		const draggables = document.querySelectorAll(".draggable");
		draggables.forEach(item => {
			if(item.getAttribute('listener') !== 'true') {
				item.addEventListener("mouseenter", handleMouseEnterDraggable);
				item.addEventListener("mouseleave", handleMouseLeaveDraggable);
				item.addEventListener("pointermove", handleMouseDragDraggable);
				item.setAttribute('listener', 'true');
			}
		});
	};

	if(!window.mobileAndTabletcheck()){
		initCursor();
		initHovers();
		jQuery(document).ajaxStop(function() {
			initHovers();
		});
	}
});