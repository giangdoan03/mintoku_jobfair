<!--<div class="header-button-regist">-->
<!--    <a href="<?php echo $var['url_registration']; ?>"><?php echo $var['button_registration']; ?></a>-->
<!--</div>-->
<div class="header__language">
	<div class="header__language__icon">
		<img class="language__icon" src="/wp-content/uploads/2023/06/country_jp.png" width="100" alt="">
	</div>
	<select id="selec" onchange="doGTranslate(this);">
		<option value="">Select Language</option>
		<option value="ja|en">English</option>
		<option value="ja|ja">日本語</option>
		<option value="ja|vi">Tiếng Việt</option>
		<option value="ja|id">Bahasa Indonesia</option>
		<option value="ja|my">ဗမာစာ</option>
		<option value="ja|tl">Filipino</option>
		<option value="ja|zh-CN">简体中文</option>
		<option value="ja|th">ไทย</option>
		<option value="ja|km">ភាសាខ្មែរ</option>
	</select>
	<script type="text/javascript">

		function doGTranslate(lang_pair) { 
			if (lang_pair.value) lang_pair = lang_pair.value; 
			if (lang_pair == '') return; 
			var lang = lang_pair.split('|')[1]; 
			var plang = location.pathname.split('/')[1]; 
			if (plang.length != 2 && plang.toLowerCase() != 'zh-cn' && plang.toLowerCase() != 'zh-tw') plang = 'ja'; 
			if (lang == 'ja') location.href = location.protocol + '//' + location.host + location.pathname.replace('/' + plang + '/', '/') + location.search; 
			else location.href = location.protocol + '//' + location.host + '/' + lang + location.pathname.replace('/' + plang + '/', '/') + location.search; 
		}

		function getFirstURLSegment() {
			// Get the current path of the URL
			var currentPath = window.location.pathname;

			// Remove any trailing slashes and split the path into segments
			var pathSegments = currentPath.split('/').filter(function (segment) {
				return segment !== '';
			});

			// Get the first segment (segment 1)
			var segment1 = '';
			if (pathSegments.length > 0) {
				segment1 = pathSegments[0];
			}

			return segment1;
		}
		// Call the function and get the first segment
		var firstSegment = getFirstURLSegment();

		// Output the first segment
		console.log("First Segment: " + firstSegment);

		if (firstSegment === 'en') {
			x = document.querySelector(".language__icon"); 
			x.setAttribute('src', 'https://blog.minnano-tokugi.com/wp-content/uploads/2023/06/country_other.png');
			$select = document.querySelector('#selec');
			$select.value = 'ja|en';
		}
		else if (firstSegment === 'vi') {
			x = document.querySelector(".language__icon"); 
			x.setAttribute('src', 'https://blog.minnano-tokugi.com/wp-content/uploads/2023/06/country_vn.png');
			$select = document.querySelector('#selec');
			$select.value = 'ja|vi';
		}
		else if (firstSegment === 'id') {
			x = document.querySelector(".language__icon"); 
			x.setAttribute('src', 'https://blog.minnano-tokugi.com/wp-content/uploads/2023/06/country_idn.png');
			$select = document.querySelector('#selec');
			$select.value = 'ja|id';
		}
		else if (firstSegment === 'my') {
			x = document.querySelector(".language__icon"); 
			x.setAttribute('src', 'https://blog.minnano-tokugi.com/wp-content/uploads/2023/07/country_mmr.png');
			$select = document.querySelector('#selec');
			$select.value = 'ja|my';
		}
		// else if(firstSegment === ''){
		//      x = document.querySelector(".language__icon"); x.setAttribute('src','/wp-content/uploads/2023/06/country_jp.png');
		//       $select = document.querySelector('#selec');
		//   $select.value = 'ja|ja';
		// }
		else if (firstSegment === 'tl') {
			x = document.querySelector(".language__icon"); 
			x.setAttribute('src', 'https://blog.minnano-tokugi.com/wp-content/uploads/2023/08/country_ph.png');
			$select = document.querySelector('#selec');
			$select.value = 'ja|tl';
		}
		else if (firstSegment === 'zh-CN') {
			x = document.querySelector(".language__icon"); 
			x.setAttribute('src', 'https://blog.minnano-tokugi.com/wp-content/uploads/2023/08/country_cn.png');
			$select = document.querySelector('#selec');
			$select.value = 'ja|zh-CN';
		}
		else if (firstSegment === 'th') {
			x = document.querySelector(".language__icon"); 
			x.setAttribute('src', 'https://blog.minnano-tokugi.com/wp-content/uploads/2023/08/country_tha.png');
			$select = document.querySelector('#selec');
			$select.value = 'ja|th';
		}
		else if (firstSegment === 'km') {
			x = document.querySelector(".language__icon"); 
			x.setAttribute('src', 'https://blog.minnano-tokugi.com/wp-content/uploads/2023/08/country_khm.png');
			$select = document.querySelector('#selec');
			$select.value = 'ja|km';
		}
		else {
			x = document.querySelector(".language__icon"); 
			x.setAttribute('src', 'https://blog.minnano-tokugi.com/wp-content/uploads/2023/06/country_jp.png');
			$select = document.querySelector('#selec');
			$select.value = 'ja|ja';
		}
	</script>
</div>