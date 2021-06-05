<?php
namespace PHPMaker2021\inplaze;

function setInnerHTMLDiv($parent, $html) {
	while ($parent->hasChildNodes()) {
		$parent->removeChild($parent->firstChild);
	}
    $tmpDoc = new \DOMDocument();
	$tmpDoc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
    foreach ($tmpDoc->getElementsByTagName('body')->item(0)->childNodes as $n) {
        $n = $parent->ownerDocument->importNode($n, true);
        $parent->appendChild($n);
    }
}

function setInnerHTMLP($element, $html) {
	$html = str_replace('&nbsp;', ' ', $html);
	$fragment = $element->ownerDocument->createDocumentFragment();
	$fragment->appendXML($html);
	$clone = $element->cloneNode();
	$clone->appendChild($fragment);
	$element->parentNode->replaceChild($clone, $element);
	return $element;
}

// ไฟล์ที่จะถูกประมวลต้องเป็น .html เท่านั้น นอกนั้นแสดงผลตามปกติโดยไม่ถูกประมวลผล
if (pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_EXTENSION) == 'html') {
	$content = ob_get_contents();
	ob_end_clean();
	$dom = new \DOMDocument();
	libxml_use_internal_errors(true);

	// ถ้าเจอข้อผิดพลาด เกิดไม่มี content ขึ้นมา ให้แจ้งเพื่อสังเกตุพฤติกรรมการเกิด
	if (empty($content)) {
		// notify(print_r([
			// 'SCRIPT_FILENAME' => $_SERVER['SCRIPT_FILENAME']
			// , 'Error_Message' => 'HTML content from ob_get_contents is empty. Please check the cause.'
		// ], 1), 'tzgmZeRAuIzsdK8JmFocCO1CYf5aLl1ieahZTX5njx2');
	} else {
		// $dom->loadHTML($content, LIBXML_NOBLANKS);
		$dom->loadHTML($content);
		$xpath = new \DomXPath($dom);
		$sub_folder = '';
		$font = 'Prompt:300,400,400i,700';

		// แก้ http เป็น https ทุกจุดที่เป็น CDN ตาม attribute href
		$elements = $xpath->query("//link[contains(@href, 'http://')]");
		foreach ($elements as $key => $element) {
			$attribute_value = $elements->item($key)->getAttribute('href');
			$attribute_value = str_replace('http://', 'https://', $attribute_value);
			$elements->item($key)->setAttribute('href', $attribute_value);
		}

		// เฉพาะของ demo template นี้เท่านั้น โดยดูจาก css เป็นหลัก
		if (($n = $xpath->query($selector = '//link[@href="demos/real-estate/real-estate.css"]')) AND !empty($n->length)) {
			require_once 'real-estate.php';
		}
		if (($n = $xpath->query($selector = '//link[@href="demos/course/course.css"]')) AND !empty($n->length)) {
			require_once 'course.php';
		}

		if ($_SERVER['SERVER_NAME'] == 'polo5.in-plaze.com') {
			// สำหรับ POLO รูปภาพทั้งหมด ส่วนใหญ่เป็นรูปเทาๆ ดูไม่สมจริง ให้เปลี่ยน URL ไปดึงเอาจากเว็บ demo มาให้หมด
			$attributes = ['src', 'style', 'href', 'data-parallax-image', 'data-thumb', 'data-bg-image', 'data-bg-parallax'];
			foreach ($attributes as $attribute) {
				$elements = $xpath->query("//*[contains(@{$attribute}, '.jpg')]|//*[contains(@{$attribute}, '.png')]");
				foreach ($elements as $key => $element) {
					$new_value = $elements->item($key)->getAttribute($attribute);
					// fb($new_value);
					if (strpos($new_value, "url(") !== false) {
						// $new_value = str_replace(array("url('"), "url('https://www.inspirothemes.com/polo/{$sub_folder}", $new_value);
						$new_value = str_replace("images", "https://www.inspirothemes.com/polo/images", $new_value);
					} else {
						$sub_folder = "";
						if (pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_BASENAME) == 'intro.html')
							$sub_folder = "preview/";
						$new_value = "https://www.inspirothemes.com/polo/{$sub_folder}{$new_value}";
					}
					// fb($new_value);
					// $new_value = str_replace("images", "https://www.inspirothemes.com/polo/images", $new_value);
					// $new_value = str_replace("polo/images/layouts", "polo/preview/images/layouts", $new_value);
					// echo/ '<pre>'.print_r($new_value, 1).'</pre>';
					$elements->item($key)->setAttribute($attribute, $new_value);
				}
			}
		}

		// สำหรับหน้า intro ของ template ให้เอาหลายๆ ส่วนออกไป
		if (pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_BASENAME) == 'intro.html') {
			$sub_folder = "preview/";
			// //div[contains(@class, 'section')][//div[contains(@class, 'counter')]]
			$elements = $xpath->query("
				//header
				|//section[@id='slider']
				|//div[@class='section center mt-0 clearfix']
				|//div[@class='section mt-0 clearfix']
				|//div[@class='row align-items-stretch']
				|//div[@class='section dark my-0 clearfix']
				|//div[@class='row align-items-stretch mx-0 intro-support-block clearfix']
				|//div[@id='section-forms']
				|//div[@id='section-shortcodes']
				|//div[@id='section-features']
				|//div[@id='section-hero']
				|//div[@id='section-testimonials']
				|//div[@id='section-demos']
				|//div[@id='section-p-generator']
				|//div[@id='section-purchase']
				|//div[@id='page-menu']
				|//footer
			");
			// Keep sale content
			$elements = $xpath->query("//header|//div[@id='section-p-generator']|//div[@id='section-purchase']|//div[@id='page-menu']|//div[@class='copyright-links']|//a[contains(@href, 'themeforest.net')]|//a[contains(@href, 'semicolonweb.com')]");
			foreach ($elements as $key => $element) {
			    $elements->item($key)->parentNode->removeChild($elements->item($key));
			}
		} else {
			// ไฟล์ไหนที่ไม่ใช่ไฟล์ intro ให้ประมวลผลตามนี้ทั้งหมดได้เลย
			$xpath->query('//html')->item(0)->setAttribute("lang", "th-TH");

			// เพิ่มฟอนต์ Prompt เข้าไปในแต่ละหน้า แต่ยังต้องไป replace sans-serif ให้เป็น 'Prompt' ในไฟล์ style.css แบบ manual อยู่ ต้องทำใน custom.css
			// https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700%7CRoboto:300,400,500,700&display=swap
			if (($n = $xpath->query($selector = '/html/head/link[contains(@href, "fonts.googleapis.com")]')) AND !empty($n->length) AND $n = $n->item(0)) {
				$n->setAttribute('href', str_replace('family=', "family={$font}|", $n->getAttribute('href')));
			}

			// เอา favicon จาก PHPMaker มาใส่ด้วย
			if (($n = $xpath->query($selector = '/html/head')) AND !empty($n->length) AND $n = $n->item(0)) {
				$link_node = $dom->createElement('link', '');
				$link_node->setAttribute('rel', 'shortcut icon');
				$link_node->setAttribute('type', 'image/x-icon');
				$link_node->setAttribute('href', 'in-plaze/favicon.ico');
				$n->appendChild($link_node);
			}

			// เอา Google Maps ที่ไม่มี API ออก
			if (($n = $xpath->query($selector = '//script[contains(@src, "YOUR-API-KEY")]')) AND !empty($n->length) AND $n = $n->item(0)) {
				fb($selector);
				$n->parentNode->removeChild($n);
				if (($n = $xpath->query($selector = '//img[contains(@src, "https://maps.googleapis.com/maps/api/")]')) AND !empty($n->length) AND $n = $n->item(0)) {
					$n->parentNode->removeChild($n);
				}
			}

			if ($template = "Canvas - Blog") {
				// หา tag ใหญ่นอกสุด ที่มี id เป็น blog และมีลูกเป็น class="post-item" แต่ไม่ใช่ single-post (หน้าเนื้อหา)
				if (($parent = $xpath->query($selector = '//div[@id="section-articles"]//div[contains(@class, "col_full")]')) AND !empty($parent->length)) {
					fb($template, 'template');
					fb($selector, 'selector');
					// เติม attribute data-inplaze ไม่ให้ tag ทั่วไปถูกประมวลผล
					$parent->item(0)->setAttribute("data-inplaze", "true");

					// หา tag ที่เป็น item เพื่อนำมาเป็น prototype ก่อน clone
					$elements = $xpath->query($selector = './/div[contains(@class, "col_one_third")]', $parent->item(0));
					if (!empty($elements->length)) {
						$elements = $elements->item(0);
						$children = [];
						$index = $xpath->query($selector = '//div[@id="section-about"]');
						$limit = "";
						if ($index->length == 1) {
							$limit = ' LIMIT 3';
						}
						$datas = ExecuteRows("SELECT *, DATE_FORMAT(Created, '%d %M %Y') AS Blog_Date FROM blog ORDER BY New DESC, Blog_ID DESC{$limit}", 2);
						if (!empty($datas)) {
							foreach ($datas as $key => $data) {
								$data['Slug'] = slugify(mb_substr($data['Title'], 0, 80));
								if (($ns = $xpath->query($selector = './/h2/a', $parent->item(0))) AND !empty($ns->length))
									$ns->item(0)->nodeValue = $data['Title'];
								$xpath->query('.//img', $elements)->item(0)->setAttribute("src", "in-plaze/upload/{$data['Image']}");
								// $xpath->query('.//p', $elements)->item(0)->nodeValue = $data['Intro'];
								$xpath->query('.//div[contains(@class,"entry-meta")]//span', $elements)->item(0)->nodeValue = $data['Blog_Date'];
								foreach ($xpath->query('.//a', $elements) as $element) {
									$element->setAttribute("href", "blog-{$data['Blog_ID']}-{$data['Slug']}");
								}
								$html_element = $xpath->query('.//div[contains(@class, "entry-content")]', $elements);
								if (!empty($data['Intro'])) {
									while ($html_element->item(0)->hasChildNodes()) {
										$html_element->item(0)->removeChild($html_element->item(0)->firstChild);
									}
									$temp = new \DOMDocument();
									$temp->preserveWhiteSpace = false;
									$temp->formatOutput = true;
									$temp->loadHTML(mb_convert_encoding(nl2br($data['Intro']), 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
									foreach ($temp->firstChild->childNodes as $child) {
										if ($child->nodeName == "#text") {
											$n = $dom->createTextNode($child->nodeValue);
										} else {
											$n = $dom->createElement($child->nodeName, $child->nodeValue);
										}
										$newnode = $html_element->item(0)->appendChild($n);
									}
								} else {
									$xpath->query('.//p', $elements)->item(0)->nodeValue = '';
								}
								if (Session(SESSION_STATUS) == 'login') {
									foreach ($xpath->query('.//a', $elements) as $element) {
										$element->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/BlogEdit/{$data['Blog_ID']}'; return false; }");
									}
								}
								if ($key % 3 == 2) {
									$elements->setAttribute("class", $elements->getAttribute("class") . " col_last");
								} else {
									$elements->setAttribute("class", str_replace('col_last', '', $elements->getAttribute("class")));
								}
								$children[$key] = $elements->cloneNode(true);
							}
							while ($parent->item(0)->hasChildNodes()) {
								$parent->item(0)->removeChild($parent->item(0)->firstChild);
							}
							$n = $dom->createElement('div', '');
							$n->setAttribute("class", "clear");
							foreach ($datas as $key => $value) {
								$parent->item(0)->appendChild($children[$key]);
								if ($key % 3 == 2)
									$parent->item(0)->appendChild($n);
							}
						}
					}
				}
			}

			// News Gallery แบบใหม่ที่แสดงผลข่าวจำนวนมากได้ดีกว่า เริ่มใช้ใน suphabeefarm.com demo.suphabeefarm.com
			if ($template = "Canvas - Blog V2") {
				// หา tag ใหญ่นอกสุด ที่มี id เป็น blog และมีลูกเป็น class="post-item" แต่ไม่ใช่ single-post (หน้าเนื้อหา)
				if (($parent = $xpath->query($selector = '//div[@id="posts"]|//div[starts-with(@class,"row posts-")]')) AND !empty($parent->length)) {
					fb($template, 'template');
					fb($selector, 'selector');
					// เติม attribute data-inplaze ไม่ให้ tag ทั่วไปถูกประมวลผล
					$parent->item(0)->setAttribute("data-inplaze", "true");
					// หา tag ที่เป็น item เพื่อนำมาเป็น prototype ก่อน clone
					$prototype = $xpath->query($selector = './/div[contains(@class, "entry")]', $parent->item(0));
					if (!empty($prototype->length)) {
						$prototype = $prototype->item(0);
						$children = [];
						$index = $xpath->query($selector = '//div[@id="section-about"]');
						$limit = 999;
						// fb($parent->item(0)->getAttribute("data-inplaze-limit"), 'limit');
						if (!empty($data_inplaze_limit = $parent->item(0)->getAttribute("data-inplaze-limit"))) {
							$limit = $data_inplaze_limit;
						}
						$datas = ExecuteRows("SELECT *, DATE_FORMAT(Created, '%d %M %Y') AS Blog_Date FROM blog ORDER BY New DESC, Blog_ID DESC LIMIT {$limit}", 2);
						if (!empty($datas)) {
							foreach ($datas as $key => $data) {
								$data['Slug'] = slugify($data['Title']);
								if (($ns = $xpath->query($selector = './/h2/a|.//h3/a', $parent->item(0))) AND !empty($ns->length)) {
									$ns->item(0)->nodeValue = $data['Title'];
								}
								$xpath->query('.//img', $prototype)->item(0)->setAttribute("src", "in-plaze/upload/{$data['Image']}");
								// $xpath->query('.//p', $prototype)->item(0)->nodeValue = $data['Intro'];
								// $xpath->query('.//ul[contains(@class,"entry-meta")]//li/text()', $prototype)->item(0)->nodeValue = $data['Blog_Date'];
								if (($ns = $xpath->query('.//*[contains(@class,"entry-meta")]//li/text()', $prototype)) AND !empty($ns->length)) {
									$ns->item(0)->nodeValue = $data['Blog_Date'];
								}
								foreach ($xpath->query('.//a', $prototype) as $element) {
									$element->setAttribute("href", "blog-{$data['Blog_ID']}-{$data['Slug']}");
									$element->removeAttribute("data-lightbox");
								}
								$html_element = $xpath->query('.//div[contains(@class, "entry-content")]', $prototype);
								// fb($data['Intro'], 'Intro');
								while ($html_element->item(0)->hasChildNodes()) {
									$html_element->item(0)->removeChild($html_element->item(0)->firstChild);
								}
								if (!empty($data['Intro'])) {
									$temp = new \DOMDocument();
									$temp->preserveWhiteSpace = false;
									$temp->formatOutput = true;
									$temp->loadHTML(mb_convert_encoding(nl2br($data['Intro']), 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
									foreach ($temp->firstChild->childNodes as $child) {
										if ($child->nodeName == "#text") {
											$n = $dom->createTextNode($child->nodeValue);
										} else {
											$n = $dom->createElement($child->nodeName, $child->nodeValue);
										}
										$newnode = $html_element->item(0)->appendChild($n);
									}
								}
								if (Session(SESSION_STATUS) == 'login') {
									foreach ($xpath->query('.//a', $prototype) as $element) {
										$element->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/BlogEdit/{$data['Blog_ID']}'; return false; }");
									}
								}
								if ($key % 3 == 2) {
									$prototype->setAttribute("class", $prototype->getAttribute("class") . " col_last");
								} else {
									$prototype->setAttribute("class", str_replace('col_last', '', $prototype->getAttribute("class")));
								}
								$children[$key] = $prototype->cloneNode(true);
							}
							while ($parent->item(0)->hasChildNodes()) {
								$parent->item(0)->removeChild($parent->item(0)->firstChild);
							}
							$n = $dom->createElement('div', '');
							$n->setAttribute("class", "clear");
							foreach ($datas as $key => $value) {
								$parent->item(0)->appendChild($children[$key]);
								if ($key % 3 == 2)
									$parent->item(0)->appendChild($n);
							}
						}
					}
				}
			}

			if ($template = "Canvas - Blog Gallery") {
				// หา tag ใหญ่นอกสุด ที่มี id เป็น blog และมีลูกเป็น class="post-item" แต่ไม่ใช่ single-post (หน้าเนื้อหา)
				if (($parent = $xpath->query($selector = '//*[@id="news-gallery"]')) AND !empty($parent->length)) {
					fb($template, 'template');
					fb($selector, 'selector');
					// เติม attribute data-inplaze ไม่ให้ tag ทั่วไปถูกประมวลผล
					$parent->item(0)->setAttribute("data-inplaze", "true");

					// หา tag ที่เป็น item เพื่อนำมาเป็น prototype ก่อน clone
					$elements = $xpath->query($selector = './/a[@data-lightbox="gallery-item"]', $parent->item(0));
					$elements = $elements->item(0);
					$children = [];
					$datas = ExecuteScalar("SELECT Images FROM blog WHERE Blog_ID = {$_GET['Blog_ID']}");
					$datas = explode(',', $datas);
					// Execute("UPDATE `blog` SET `View` = View + 1 WHERE `blog`.`Blog_ID` = ?", array($_GET['Blog_ID']));
					if (!empty($datas)) {
						foreach ($datas as $key => $data) {
							$elements->setAttribute("href", "in-plaze/upload/{$data}");
							$xpath->query('.//img', $elements)->item(0)->setAttribute("src", "in-plaze/upload/{$data}");
							$children[$key] = $elements->cloneNode(true);
						}
						while ($parent->item(0)->hasChildNodes()) {
							$parent->item(0)->removeChild($parent->item(0)->firstChild);
						}
						foreach ($datas as $key => $value) {
							$parent->item(0)->appendChild($children[$key]);
						}
					}
				}
			}

			if ($template = "Polo - Blog") {
				// หา tag ใหญ่นอกสุด ที่มี id เป็น blog และมีลูกเป็น class="post-item" แต่ไม่ใช่ single-post (หน้าเนื้อหา)
				if (($parent = $xpath->query($selector = '//*[@id="blog" and not(@class="single-post")][div[contains(@class, "post-item")]]')) AND !empty($parent->length)) {
					fb($template, 'template');
					fb($selector, 'selector');
					// เติม attribute data-inplaze ไม่ให้ tag ทั่วไปถูกประมวลผล
					$parent->item(0)->setAttribute("data-inplaze", "true");

					// หา tag ที่เป็น item เพื่อนำมาเป็น prototype ก่อน clone
					$elements = $xpath->query($selector = './/div[contains(@class, "post-item")]', $parent->item(0));
					$elements = $elements->item(0);
					$children = [];
					$datas = ExecuteRows("SELECT *, DATE_FORMAT(Created, '%d %M %Y') AS Blog_Date FROM blog ORDER BY Priority, Blog_ID DESC", 2);
					if (!empty($datas)) {
						foreach ($datas as $key => $data) {
							$data['Slug'] = slugify(mb_substr($data['Title'], 0, 80));
							if (($ns = $xpath->query($selector = './/h2/a', $parent->item(0))) AND !empty($ns->length))
								$ns->item(0)->nodeValue = $data['Title'];
							$xpath->query('.//img', $elements)->item(0)->setAttribute("src", "in-plaze/upload/{$data['Image']}");
							$xpath->query('.//p', $elements)->item(0)->nodeValue = $data['Content'];
							$xpath->query('.//span[@class="post-meta-date"]/text()', $elements)->item(0)->nodeValue = $data['Blog_Date'];
							foreach ($xpath->query('.//a', $elements) as $element) {
								$element->setAttribute("href", "blog-{$data['Blog_ID']}-{$data['Slug']}");
							}
							foreach ($xpath->query('.//span[@class="post-meta-comments"]', $elements) as $element) {
								$element->parentNode->removeChild($element);
							}
							$element = $xpath->query('.//span[@class="post-meta-category"]', $elements);
							if ($element->length > 1) {
								$xpath->query('.//span[@class="post-meta-category"]', $elements)->item(0)->nodeValue = $data['New'] == 1 ? "New" : "";
								if ($data['New'] != 1)
									foreach ($xpath->query('.//span[@class="post-meta-category"]', $elements) as $element)
										$element->parentNode->removeChild($element);
							}
							if (Session(SESSION_STATUS) == 'login')
								foreach ($xpath->query('.//a', $elements) as $element)
									$element->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/BlogEdit/{$data['Blog_ID']}'; return false; }");
							$children[$key] = $xpath->query('//div[contains(@class, "post-item")]', $elements)->item(0)->cloneNode(true);
						}
						while ($parent->item(0)->hasChildNodes()) {
							$parent->item(0)->removeChild($parent->item(0)->firstChild);
						}
						foreach ($datas as $key => $value) {
							$parent->item(0)->appendChild($children[$key]);
						}
					}
				}
			}

			if ($template = "Polo - Blog Detail") {
				// หา tag ใหญ่นอกสุด ที่มี id เป็น blog และมีลูกเป็น single-post (หน้าเนื้อหา)
				if (($parent = $xpath->query($selector = '//*[@id="blog" and @class="single-post"][div[contains(@class, "post-item")]]')) AND !empty($parent->length)) {
					fb($template, 'template');
					fb($selector, 'selector');
					$data = ExecuteRow("SELECT *, DATE_FORMAT(Created, '%d %M %Y') AS Blog_Date FROM blog WHERE Blog_ID = {$_GET['Blog_ID']}", 2);
					if (!empty($data)) {
						$elements = $parent->item(0);
						// เติม attribute data-inplaze ไม่ให้ tag ทั่วไปถูกประมวลผล
						$elements->setAttribute("data-inplaze", "true");
						if (($ns = $xpath->query($selector = './/h2', $parent->item(0))) AND !empty($ns->length))
							$ns->item(0)->nodeValue = $data['Title'];
						$xpath->query('.//img', $elements)->item(0)->setAttribute("src", "in-plaze/upload/{$data['Image']}");
						$xpath->query('.//span[@class="post-meta-date"]/text()', $elements)->item(0)->nodeValue = $data['Blog_Date'];
						foreach ($xpath->query('.//span[@class="post-meta-comments"]', $elements) as $element)
							$element->parentNode->removeChild($element);
						foreach ($xpath->query('.//div[@id="comments"]', $elements) as $element)
							$element->parentNode->removeChild($element);
						foreach ($xpath->query('.//div[@id="respond"]', $elements) as $element)
							$element->parentNode->removeChild($element);
						foreach ($xpath->query('.//div[contains(@class, "post-navigation")]', $elements) as $element)
							$element->parentNode->removeChild($element);
						if ($data['New'] == 1) {
							$xpath->query('.//span[@class="post-meta-category"]/a/text()', $elements)->item(0)->nodeValue = "New";
						} else {
							foreach ($xpath->query('.//span[@class="post-meta-category"]', $elements) as $element)
								$element->parentNode->removeChild($element);
						}
						if (Session(SESSION_STATUS) == 'login') {
							foreach ($xpath->query('.//a', $elements) as $element) {
								$element->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/BlogEdit/{$data['Blog_ID']}'; return false; }");
							}
						}
						$html_element = $xpath->query('.//div[contains(@class, "post-item-description")]', $elements);
						if (!empty($data['Content'])) {
							foreach ($xpath->query('./p', $html_element->item(0)) as $element)
								$element->parentNode->removeChild($element);
							foreach ($xpath->query('./div[contains(@class, "blockquote")]', $html_element->item(0)) as $element)
								$element->parentNode->removeChild($element);
							$temp = new \DOMDocument();
							$temp->preserveWhiteSpace = false;
							$temp->formatOutput = true;
							$temp->loadHTML(mb_convert_encoding(nl2br($data['Content']), 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
							foreach ($temp->firstChild->childNodes as $child) {
								// fb($child->nodeName);
								// fb($child->nodeValue);
								if ($child->nodeName == "#text") {
									$n = $dom->createTextNode($child->nodeValue);
								} else {
									$n = $dom->createElement($child->nodeName, $child->nodeValue);
								}
								$newnode = $html_element->item(0)->appendChild($n);
							}
						}
					}
				}
			}

			// ควรจะย้ายไป map กับ attribute ใน tag จะดีกว่ามั้ย
			if ($_SERVER["SCRIPT_NAME"] == "/product.html") {
				fb("Custom", 'template');
				fb($_SERVER["SCRIPT_NAME"], 'selector');
				$data = ExecuteRow("SELECT product.*, category.Name AS Category_Name FROM product JOIN category USING (Category_ID) WHERE Product_ID = {$_GET['Product_ID']}", 2);
				// fb($data);
				if (!empty($data['Name']))
					$site_title = $data['Name'];
				if (!empty($data['Intro']))
					$meta_description = $data['Intro'];
				$elements = $xpath->query($selector = "//div[contains(@class,'heading-block')]/h1|//section//h1");
				if (!empty($elements->length) AND !empty($data['Name']))
					$elements->item(0)->nodeValue = htmlspecialchars_decode(htmlspecialchars($data['Name']));
				$elements = $xpath->query($selector = "//section//h1/following-sibling::span");
				if (!empty($elements->length) AND !empty($data['Intro']))
					$elements->item(0)->nodeValue = htmlspecialchars_decode(htmlspecialchars($data['Intro']));
				$elements = $xpath->query($selector = "//div[contains(@class,'heading-block')]/h2");
				if (!empty($elements->length) AND !empty($data['Name']))
					$elements->item(0)->nodeValue = htmlspecialchars_decode(htmlspecialchars($data['Name']));
				$elements = $xpath->query($selector = "//div[contains(@class,'heading-block')]/span");
				if (!empty($elements->length) AND !empty($data['Category_Name']))
					$elements->item(0)->nodeValue = htmlspecialchars_decode(htmlspecialchars($data['Category_Name']));
				$elements = $xpath->query($selector = "//div[contains(@class,'fancy-title')]/h2");
				if (!empty($elements->length) AND !empty($data['Name']))
					$elements->item(0)->nodeValue = htmlspecialchars_decode(htmlspecialchars($data['Name']));
				$elements = $xpath->query($selector = "//div[contains(@class,'portfolio-single-content')]/p");
				if (!empty($elements->length) AND !empty($data['Description'])) {
					while ($elements->item(0)->hasChildNodes())
						$elements->item(0)->removeChild($elements->item(0)->firstChild);
					$temp = new \DOMDocument();
					$temp->preserveWhiteSpace = false;
					$temp->formatOutput = true;
					$temp->loadHTML(mb_convert_encoding(nl2br($data['Description']), 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
					foreach ($temp->firstChild->childNodes as $child) {
						// fb($child->nodeName);
						// fb($child->nodeValue);
						if ($child->nodeName == "#text") {
							$n = $dom->createTextNode($child->nodeValue);
						} else {
							$n = $dom->createElement($child->nodeName, $child->nodeValue);
						}
						$newnode = $elements->item(0)->appendChild($n);
					}
				}
				$elements = $xpath->query($selector = "//div[contains(@class,'portfolio-single-image')]/a/img");
				if (!empty($elements->length) AND !empty($data['Image'])) {
					// fb($selector);
					$elements->item(0)->setAttribute("src", "in-plaze/upload/{$data['Image']}");
					$elements->item(0)->setAttribute("alt", $data['Name']);
				}
				if (!empty($data['Images'])) {
					$data['Images'] = array_merge([$data['Image']], explode(',', $data['Images']));
				}
				$elements = $xpath->query($selector = '//a[@data-lightbox="gallery-item"]/img');
				if (!empty($elements->length)) {
					$parent = $elements->item(0)->parentNode->parentNode;
					// fb($selector);
					// fb($parent->nodeName);
					$children = [];
					if (!empty($data['Images'])) {
						foreach ($data['Images'] as $key => $value) {
							// fb($value);
							// fb($elements->item(0)->parentNode->childNodes->item(1)->firstChild->nodeValue);
							// fb($elements->item(0)->parentNode->childNodes->item(1)->nodeValue);
							// fb($elements->item(0)->nodeName);
							// fb($elements->item(0)->parentNode->nodeName);
							$elements->item(0)->parentNode->setAttribute("href", "in-plaze/upload/{$value}");
							$elements->item(0)->setAttribute("src", "in-plaze/upload/{$value}");
							$elements->item(0)->setAttribute("alt", $data['Name']);
							// $elements->item(0)->parentNode->childNodes->item(1)->firstChild->nodeValue = $value['Name'];
							$children[$key] = $elements->item(0)->parentNode->cloneNode(true);
						}
						$parent->nodeValue = "";
						foreach ($data['Images'] as $key => $value) {
							$parent->appendChild($children[$key]);
						}
						// if (!empty($value = ExecuteScalar("SELECT Images FROM product WHERE Product_ID = {$_GET['Product_ID']}"))) {
							// $elements->item(0)->setAttribute("src", "in-plaze/upload/{$value}");
						// }
					} else {
						$element = $xpath->query($selector = '//a[@data-lightbox="gallery-item"]/ancestor::div[@class="col_full clearfix"]');
						$element->item(0)->parentNode->removeChild($element->item(0));
					}
				}
				if ($template = "Canvas - Portfolio Single Image") {
					// หา tag ใหญ่นอกสุด ที่บอก module ของข้อมูล กรณีนี้คือ Image ในหน้า Portfolio Single
					if (($parent = $xpath->query($selector = "//div[contains(@class,'portfolio-single-image')]")) AND !empty($parent->length)) {
						fb($template, 'template');
						fb($selector, 'selector');
						// เติม attribute data-inplaze ไม่ให้ tag ทั่วไปถูกประมวลผล
						$parent->item(0)->setAttribute("data-inplaze", "true");

						// หา tag ที่เป็น item เพื่อนำมาเป็น prototype ก่อน clone
						$prototype = $xpath->query($selector = './/div[@data-thumb]', $parent->item(0))->item(0);
						$children = [];
						$datas = $data['Images'];
						// fb($datas);
						if (!empty($datas)) {
							foreach ($datas as $key => $data) {
								// $data['Slug'] = slugify(mb_substr($data['Name'], 0, 80));
								 
									// $ns->item(0)->nodeValue = $data['Name'];
								if (!empty($data) AND file_exists("in-plaze/upload/{$data}") AND exif_imagetype("in-plaze/upload/{$data}") !== false AND filter_var("in-plaze/upload/{$data}", FILTER_VALIDATE_URL) === false) {
									$prototype->setAttribute("data-thumb", "in-plaze/upload/{$data}");
									$xpath->query('.//img', $prototype)->item(0)->setAttribute("src", "in-plaze/upload/{$data}");
								}
								if (Session(SESSION_STATUS) == 'login')
									foreach ($xpath->query('.//a', $prototype) as $element)
										$element->setAttribute("href", "in-plaze/ProductEdit/{$_GET['Product_ID']}");
								$children[$key] = $prototype->cloneNode(true);
							}
							$children_parent = $xpath->query($selector = './/div[contains(@class, "slider-wrap")]', $parent->item(0))->item(0);
							while ($children_parent->hasChildNodes()) {
								$children_parent->removeChild($children_parent->firstChild);
							}
							foreach ($datas as $key => $value) {
								$children_parent->appendChild($children[$key]);
							}
						}
					}
				}
				if ($template = "Canvas - Related Portfolio") {
					// หา tag ใหญ่นอกสุด ที่มี id เป็น portfolio และมีลูกเป็น article class="owl-item"
					if (($parent = $xpath->query($selector = "//div[@id='related-portfolio']")) AND !empty($parent->length)) {
						fb($template, 'template');
						fb($selector, 'selector');
						// เติม attribute data-inplaze ไม่ให้ tag ทั่วไปถูกประมวลผล
						$parent->item(0)->setAttribute("data-inplaze", "true");
						// หา tag ที่เป็น item เพื่อนำมาเป็น prototype ก่อน clone
						$prototype = $xpath->query($selector = './/div[contains(@class, "oc-item")]', $parent->item(0))->item(0);
						// fb($prototype->nodeName);
						$children = [];
						// $datas = ExecuteRows("SELECT *, Description AS Intro, Category_ID AS ID, 'Category' AS Table_Name FROM category WHERE Category_ID IN (SELECT DISTINCT(Category_ID) FROM product) ORDER BY Priority", 2);
						// if (count($datas) == 1)
						// fb($datas);
						if (!empty($datas = ExecuteRows("SELECT *, Product_ID AS ID FROM product WHERE Category_ID = 1 AND Product_ID != {$_GET['Product_ID']} ORDER BY Priority LIMIT 10", 2))) {
							foreach ($datas as $key => $data) {
								// fb($data);
								$data['Slug'] = slugify(mb_substr($data['Name'], 0, 80));
								// fb($data['Slug']);
								if (($ns = $xpath->query($selector = './/h3/a', $prototype)) AND !empty($ns->length)) {
									$ns->item(0)->nodeValue = $data['Name'];
								}
								if (($ns = $xpath->query($selector = './/span', $prototype)) AND !empty($ns->length)) {
									$ns->item(0)->nodeValue = $data['Intro'];
								}
								if (($ns = $xpath->query($selector = './/img', $prototype)) AND !empty($ns->length) AND !empty($data['Image']) AND file_exists("in-plaze/upload/{$data['Image']}") AND exif_imagetype("in-plaze/upload/{$data['Image']}") !== false AND filter_var("in-plaze/upload/{$data['Image']}", FILTER_VALIDATE_URL) === false) {
									$ns->item(0)->setAttribute("src", "in-plaze/upload/{$data['Image']}");
								}
								foreach (($ns = $xpath->query($selector = './/a', $prototype)) as $n) {
									$n->setAttribute("href", "product-{$data['ID']}-{$data['Slug']}");
								}
								if (Session(SESSION_STATUS) == 'login') {
									foreach (($ns = $xpath->query($selector = './/a', $prototype)) as $n) {
										$n->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/ProductEdit/{$data['ID']}'; return false; }");
									}
								}
								$children[$key] = $prototype->cloneNode(true);
							}
							while ($parent->item(0)->hasChildNodes()) {
								$parent->item(0)->removeChild($parent->item(0)->firstChild);
							}
							foreach ($datas as $key => $value) {
								$parent->item(0)->appendChild($children[$key]);
							}
						}
					}
				}
			}

			if ($template = "Canvas - Portfolio") {
				// หา tag ใหญ่นอกสุด ที่มี id เป็น portfolio และมีลูกเป็น article class="portfolio-item" แต่ไม่ใช่ ...
				if (($parent = $xpath->query($selector = '//*[@id="portfolio"][article[contains(@class,"portfolio-item")]]')) AND !empty($parent->length)) {
					fb($template, 'template');
					fb($selector, 'selector');
					// เติม attribute data-inplaze ไม่ให้ tag ทั่วไปถูกประมวลผล
					$parent->item(0)->setAttribute("data-inplaze", "true");

					// หา tag ที่เป็น item เพื่อนำมาเป็น prototype ก่อน clone
					$prototype = $xpath->query($selector = '//article[contains(@class,"portfolio-item")]', $parent->item(0))->item(0);
					$children = [];
					$datas = ExecuteRows("SELECT *, Description AS Intro, Category_ID AS ID, 'Category' AS Table_Name FROM category WHERE Category_ID IN (SELECT DISTINCT(Category_ID) FROM product) ORDER BY Priority", 2);
					if (count($datas) == 1)
						$datas = ExecuteRows("SELECT *, Product_ID AS ID, 'Product' AS Table_Name FROM product ORDER BY Priority LIMIT 6", 2);
					// fb($datas);
					if (!empty($datas)) {
						foreach ($datas as $key => $data) {
							$data['Slug'] = slugify(mb_substr($data['Name'], 0, 80));
							if (($ns = $xpath->query($selector = './/h2/a', $parent->item(0))) AND !empty($ns->length))
								$ns->item(0)->nodeValue = $data['Name'];
							if (!empty($data['Image']) AND file_exists("in-plaze/upload/{$data['Image']}") AND exif_imagetype("in-plaze/upload/{$data['Image']}") !== false AND filter_var("in-plaze/upload/{$data['Image']}", FILTER_VALIDATE_URL) === false) {
								$xpath->query('.//img', $prototype)->item(0)->setAttribute("src", "in-plaze/upload/{$data['Image']}");
							}
							if (!empty($element = $xpath->query('.//h3/a', $prototype)->item(0)))
								$element->nodeValue = $data['Name'];
							if (!empty($element = $xpath->query('.//span', $prototype)->item(0)))
								$element->nodeValue = $data['Intro'];
							foreach ($xpath->query('.//a', $prototype) as $element) {
								$data['Table_Name_HTML'] = strtolower($data['Table_Name']);
								$element->setAttribute("href", "{$data['Table_Name_HTML']}-{$data['ID']}-{$data['Slug']}");
							}
							if (Session(SESSION_STATUS) == 'login')
								foreach ($xpath->query('.//a', $prototype) as $element)
									$element->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/{$data['Table_Name']}Edit/{$data['ID']}'; return false; }");
							$children[$key] = $xpath->query('//article[contains(@class,"portfolio-item")]', $prototype)->item(0)->cloneNode(true);
						}
						while ($parent->item(0)->hasChildNodes()) {
							$parent->item(0)->removeChild($parent->item(0)->firstChild);
						}
						foreach ($datas as $key => $value) {
							$parent->item(0)->appendChild($children[$key]);
						}
					}
				}
			}

			if ($template = 'Canvas - Portfolio @ category.html : Dynamic Product') {
				if (
					!empty($_GET['Category_ID']) AND
					!empty($datas = ExecuteRows("SELECT *, FORMAT(Price, 0) AS Price FROM product WHERE Category_ID = {$_GET['Category_ID']} ORDER BY New DESC", 2)) AND
					($firstChild = $xpath->query($selector = '//*[@id="category"]/article[contains(@class,"portfolio-item")]')) AND
					!empty($firstChild->length)
				) {
					foreach ($ns = $xpath->query('//section[@id="page-title"]//h1') as $n) { $n->nodeValue = ExecuteScalar("SELECT Name FROM category WHERE Category_ID = {$_GET['Category_ID']}"); }
					$prototype = $firstChild->item(0)->cloneNode(true);
					$parent = $firstChild->item(0)->parentNode;
					$parent->setAttribute('data-inplaze', true);
					while ($parent->hasChildNodes()) $parent->removeChild($parent->firstChild);
					foreach ($datas as $data) {
						$newNode = $prototype->cloneNode(true);
						$data['Slug'] = slugify($data['Name']);
						foreach ($ns = $xpath->query('.//img', $newNode) as $n) { $n->setAttribute("src", "in-plaze/upload/{$data['Image']}"); }
						foreach ($ns = $xpath->query('.//img', $newNode) as $n) { $n->setAttribute("alt", $data['Name']); }
						foreach ($ns = $xpath->query('.//h3/a', $newNode) as $n) { $n->nodeValue = $data['Name']; }
						foreach ($ns = $xpath->query('.//a', $newNode) as $n) { $n->setAttribute("href", "product-{$data['Product_ID']}-{$data['Slug']}"); }
						foreach ($ns = $xpath->query('.//span', $newNode) as $n) { $n->nodeValue = empty($data['Intro']) ? '' : $data['Intro']; }
						if (Session(SESSION_STATUS) == 'login') {
							foreach ($ns = $xpath->query('.//a', $newNode) as $n) {
								$n->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/ProductEdit/{$data['Product_ID']}'; return false; }");
							}
						}
						$parent->appendChild($newNode);
					}
				}
			}

			if ($template = 'Canvas - Portfolio Single @ portfolio.html : Product Detail') {
				if (
					!empty($_GET['Product_ID']) AND
					!empty($data = ExecuteRow("SELECT * FROM product WHERE Product_ID = {$_GET['Product_ID']}", 2)) AND
					($parent = $xpath->query($selector = '//div[contains(@class,"row")][div[contains(@class,"portfolio-single-content")]]')) AND
					!empty($parent->length)
				) {
					foreach ($ns = $xpath->query('//section[@id="page-title"]//h1') as $n) { $n->nodeValue = $data['Name']; }
					$parent = $parent->item(0);
					$parent->setAttribute('data-inplaze', true);
					foreach ($ns = $xpath->query('.//img', $parent) as $n) { $n->setAttribute("src", "in-plaze/upload/{$data['Image']}"); }
					foreach ($ns = $xpath->query('.//img', $parent) as $n) { $n->setAttribute("alt", $data['Name']); }
					// foreach ($ns = $xpath->query('.//h2', $parent) as $n) { $n->nodeValue = $data['Name']; }
					foreach ($ns = $xpath->query('.//p', $parent) as $n) { setInnerHTMLP($n, empty($data['Description']) ? '' : nl2br($data['Description'])); }
					if (Session(SESSION_STATUS) == 'login') {
						foreach ($ns = $xpath->query('.//a|.//h2|.//p', $parent) as $n) {
							$n->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/ProductEdit/{$data['Product_ID']}'; return false; }");
							$n->setAttribute("style", "cursor: pointer;");
						}
					}
				}
			}

			if ($template = 'Canvas - Portfolio Single @ portfolio.html : Dynamic Related Product') {
				if (
					!empty($_GET['Product_ID']) AND
					!empty($datas = ExecuteRows("SELECT *, FORMAT(Price, 0) AS Price FROM product WHERE Category_ID = (SELECT Category_ID FROM product WHERE Product_ID = {$_GET['Product_ID']}) ORDER BY RAND() LIMIT 12", 2)) AND
					($firstChild = $xpath->query($selector = '//div[contains(@class,"oc-item")][ancestor::div[@id="related-portfolio"]]')) AND
					!empty($firstChild->length)
				) {
					$prototype = $firstChild->item(0)->cloneNode(true);
					$parent = $firstChild->item(0)->parentNode;
					$parent->setAttribute('data-inplaze', true);
					while ($parent->hasChildNodes()) $parent->removeChild($parent->firstChild);
					foreach ($datas as $data) {
						$newNode = $prototype->cloneNode(true);
						$data['Slug'] = slugify($data['Name']);
						foreach ($ns = $xpath->query('.//img', $newNode) as $n) { $n->setAttribute("src", "in-plaze/upload/{$data['Image']}"); }
						foreach ($ns = $xpath->query('.//img', $newNode) as $n) { $n->setAttribute("alt", $data['Name']); }
						foreach ($ns = $xpath->query('.//h3/a', $newNode) as $n) { $n->nodeValue = $data['Name']; }
						// foreach ($ns = $xpath->query('.//a', $newNode) as $n) { $n->setAttribute("href", "portfolio.html?Product_ID={$data['Product_ID']}"); }
						foreach ($ns = $xpath->query('.//a', $newNode) as $n) { $n->setAttribute("href", "product-{$data['Product_ID']}-{$data['Slug']}"); }
						foreach ($ns = $xpath->query('.//span', $newNode) as $n) { $n->nodeValue = empty($data['Intro']) ? '' : $data['Intro']; }
						if (Session(SESSION_STATUS) == 'login') {
							foreach ($ns = $xpath->query('.//a', $newNode) as $n) {
								$n->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/ProductEdit/{$data['Product_ID']}'; return false; }");
							}
						}
						$parent->appendChild($newNode);
					}
				}
			}

			if ($template = 'Canvas - Blog Single @ blog.html : Blog Detail') {
				if (
					!empty($_GET['Blog_ID']) AND
					!empty($data = ExecuteRow("SELECT *, DATE_FORMAT(Created, '%d %M %Y') AS Blog_Date FROM blog WHERE Blog_ID = {$_GET['Blog_ID']}", 2)) AND
					($parent = $xpath->query($selector = '//div[contains(@class,"entry")][parent::div[contains(@class,"single-post")]]')) AND
					!empty($parent->length)
				) {
					fb($data);
					foreach ($ns = $xpath->query('//section[@id="page-title"]//h1') as $n) { $n->nodeValue = $data['Title']; }
					$parent = $parent->item(0);
					$parent->setAttribute('data-inplaze', true);
					foreach ($ns = $xpath->query('.//img', $parent) as $n) { $n->setAttribute("src", "in-plaze/upload/{$data['Image']}"); }
					foreach ($ns = $xpath->query('.//img', $parent) as $n) { $n->setAttribute("alt", $data['Title']); }
					foreach ($ns = $xpath->query('.//div[@class="entry-title"]//p', $parent) as $n) { $n->nodeValue = empty($data['Intro']) ? '' : $data['Intro']; }
					foreach ($ns = $xpath->query('.//i/following-sibling::text()', $parent) as $n) { $n->nodeValue = empty($data['Blog_Date']) ? '' : $data['Blog_Date']; }
					foreach ($ns = $xpath->query('.//div[contains(@class,"entry-content")]/p', $parent) as $n) { setInnerHTMLP($n, empty($data['Content']) ? '' : $data['Content']); }
					foreach ($ns = $xpath->query('.//div[contains(@class,"tagcloud")]', $parent) as $n) { $n->parentNode->removeChild($n); }
					foreach ($ns = $xpath->query('.//div[contains(@class,"si-share")]', $parent) as $n) { $n->parentNode->removeChild($n); }
					if (Session(SESSION_STATUS) == 'login') {
						foreach ($ns = $xpath->query('.//a|.//h2|.//p', $parent) as $n) {
							$n->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/BlogEdit/{$data['Blog_ID']}'; return false; }");
							$n->setAttribute("style", "cursor: pointer;");
						}
					}
				}
			}

			if ($template = 'Canvas - Blog Single @ blog.html : Dynamic Related Blog (Post)') {
				if (
					!empty($_GET['Blog_ID']) AND
					!empty($datas = ExecuteRows("SELECT *, DATE_FORMAT(Created, '%d %M %Y') AS Blog_Date FROM blog ORDER BY RAND() LIMIT 4", 2)) AND
					($firstChild = $xpath->query($selector = '//div[contains(@class,"entry")][parent::div[contains(@class,"related-posts")]]')) AND
					!empty($firstChild->length)
				) {
					$prototype = $firstChild->item(0)->cloneNode(true);
					$parent = $firstChild->item(0)->parentNode;
					$parent->setAttribute('data-inplaze', true);
					while ($parent->hasChildNodes()) $parent->removeChild($parent->firstChild);
					foreach ($datas as $data) {
						$newNode = $prototype->cloneNode(true);
						$data['Slug'] = slugify($data['Title']);
						foreach ($ns = $xpath->query('.//img', $newNode) as $n) { $n->setAttribute("src", "in-plaze/upload/{$data['Image']}"); }
						foreach ($ns = $xpath->query('.//img', $newNode) as $n) { $n->setAttribute("alt", $data['Title']); }
						foreach ($ns = $xpath->query('.//h3/a', $newNode) as $n) { $n->nodeValue = $data['Title']; }
						foreach ($ns = $xpath->query('.//i/following-sibling::text()', $parent) as $n) { $n->nodeValue = empty($data['Blog_Date']) ? '' : $data['Blog_Date']; }
						foreach ($ns = $xpath->query('.//a', $newNode) as $n) { $n->setAttribute("href", "blog-{$data['Blog_ID']}-{$data['Slug']}"); }
						foreach ($ns = $xpath->query('.//span', $newNode) as $n) { $n->nodeValue = empty($data['Intro']) ? '' : $data['Intro']; }
						if (Session(SESSION_STATUS) == 'login') {
							foreach ($ns = $xpath->query('.//a', $newNode) as $n) {
								$n->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/BlogEdit/{$data['Blog_ID']}'; return false; }");
							}
						}
						$parent->appendChild($newNode);
					}
				}
			}

			if ($template = "Canvas - Portfolio (but for product list in each category)") {/* 
				// หา tag ใหญ่นอกสุด ที่มี id เป็น portfolio และมีลูกเป็น article class="portfolio-item" แต่ไม่ใช่ ...
				if (($parent = $xpath->query($selector = '//*[@id="category"][article[contains(@class,"portfolio-item")]]')) AND !empty($parent->length)) {
					fb($template, 'template');
					fb($selector, 'selector');
					// เติม attribute data-inplaze ไม่ให้ tag ทั่วไปถูกประมวลผล
					$parent->item(0)->setAttribute("data-inplaze", "true");

					// หา tag ที่เป็น item เพื่อนำมาเป็น prototype ก่อน clone
					$prototype = $xpath->query($selector = '//article[contains(@class,"portfolio-item")]', $parent->item(0))->item(0);
					$children = [];
					$datas = ExecuteRows("SELECT *, Description AS Intro, Category_ID AS ID, 'Category' AS Table_Name FROM category WHERE Category_ID IN (SELECT DISTINCT(Category_ID) FROM product) ORDER BY Priority", 2);
					if (count($datas) == 1)
						$datas = ExecuteRows("SELECT *, Product_ID AS ID, 'Product' AS Table_Name FROM product ORDER BY Priority LIMIT 6", 2);
					// fb($datas);
					if (!empty($datas)) {
						foreach ($datas as $key => $data) {
							$data['Slug'] = slugify(mb_substr($data['Name'], 0, 80));
							if (($ns = $xpath->query($selector = './/h2/a', $parent->item(0))) AND !empty($ns->length))
								$ns->item(0)->nodeValue = $data['Name'];
							if (!empty($data['Image']) AND file_exists("in-plaze/upload/{$data['Image']}") AND exif_imagetype("in-plaze/upload/{$data['Image']}") !== false AND filter_var("in-plaze/upload/{$data['Image']}", FILTER_VALIDATE_URL) === false) {
								$xpath->query('.//img', $prototype)->item(0)->setAttribute("src", "in-plaze/upload/{$data['Image']}");
							}
							if (!empty($element = $xpath->query('.//h3/a', $prototype)->item(0)))
								$element->nodeValue = $data['Name'];
							if (!empty($element = $xpath->query('.//span', $prototype)->item(0)))
								$element->nodeValue = $data['Intro'];
							foreach ($xpath->query('.//a', $prototype) as $element) {
								$data['Table_Name_HTML'] = strtolower($data['Table_Name']);
								$element->setAttribute("href", "{$data['Table_Name_HTML']}-{$data['ID']}-{$data['Slug']}");
							}
							if (Session(SESSION_STATUS) == 'login')
								foreach ($xpath->query('.//a', $prototype) as $element)
									$element->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/{$data['Table_Name']}Edit/{$data['ID']}'; return false; }");
							$children[$key] = $xpath->query('//article[contains(@class,"portfolio-item")]', $prototype)->item(0)->cloneNode(true);
						}
						while ($parent->item(0)->hasChildNodes()) {
							$parent->item(0)->removeChild($parent->item(0)->firstChild);
						}
						foreach ($datas as $key => $value) {
							$parent->item(0)->appendChild($children[$key]);
						}
					}
				}
			 */}

			if ($_SERVER["SCRIPT_NAME"] == "/index.html") {
				// $elements = $xpath->query($selector = '//div[@class="portfolio-desc"]/span');
				$elements = $xpath->query($selector = '//*[@id="portfolio"]/article[1]/div[2]/span'); // ไม่รองรับ Canvas ใหม่ เพราะมี div class="grid-inner" ขึ้นมาครอบเฉยเลย
				// $elements = $xpath->query($selector = '//*[@id="portfolio"]/article[1]/div[2]/span|//*[@id="portfolio"]/article[1]/div/div[2]/span');
				if (!empty($elements->length)) {
					$parent = $elements->item(0)->parentNode->parentNode->parentNode;
					$children = [];
					$categories = ExecuteRows("SELECT * FROM category ORDER BY Priority", 2);
					if (!empty($categories)) {
						foreach ($categories as $key => $value) {
							$elements->item(0)->nodeValue = $value['Name'];
							$elements->item(0)->parentNode->childNodes->item(1)->firstChild->nodeValue = $value['Name'];
							$elements->item(0)->parentNode->childNodes->item(1)->firstChild->setAttribute("href", "category-{$value['Category_ID']}-{$value['Name']}");
							$elements->item(0)->parentNode->parentNode->childNodes->item(0)->childNodes->item(1)->setAttribute("src", "in-plaze/upload/{$value['Image']}");
							$elements->item(0)->parentNode->parentNode->childNodes->item(0)->childNodes->item(1)->setAttribute("alt", $value['Name']);
							if (Session(SESSION_STATUS) == 'login') {
								$elements->item(0)->parentNode->childNodes->item(1)->firstChild->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/CategoryEdit/{$value['Category_ID']}'; return false; }");
								$elements->item(0)->parentNode->childNodes->item(1)->firstChild->setAttribute("style", "cursor: pointer;" . $elements->item(0)->parentNode->childNodes->item(1)->firstChild->getAttribute("style"));
							}
							$children[$key] = $elements->item(0)->parentNode->parentNode->cloneNode(true);
						}
						$parent->nodeValue = "";
						foreach ($categories as $key => $value) {
							$parent->appendChild($children[$key]);
						}
					}
				}
			}

			// หากต้องการให้หน้าแรก แสดงสินค้าจาก category 1 เท่านั้น
			// $_GET['Category_ID'] = 1;
			if ($_SERVER["SCRIPT_NAME"] == "/index.html") {
				$elements = $xpath->query($selector = '//*[@id="single-category-portfolio"]/article[1]/div[2]/span');
				if (!empty($elements->length)) {
					$parent = $elements->item(0)->parentNode->parentNode->parentNode;
					$parent->setAttribute("data-inplaze", "true");
					$children = [];
					$where = '';
					if (!empty($_GET['Category_ID']))
						$where = "WHERE Category_ID = {$_GET['Category_ID']}";
					$products = ExecuteRows("SELECT * FROM product $where ORDER BY Priority LIMIT 6", 2);
					if (!empty($products)) {
						foreach ($products as $key => $value) {
							$value['Slug'] = slugify($value['Name']);
							$elements->item(0)->nodeValue = $value['Name'];
							$elements->item(0)->parentNode->childNodes->item(1)->firstChild->nodeValue = $value['Name'];
							$elements->item(0)->parentNode->childNodes->item(1)->firstChild->setAttribute("href", "product-{$value['Product_ID']}-{$value['Slug']}");
							if (!empty($value['Image']))
								$elements->item(0)->parentNode->parentNode->childNodes->item(0)->childNodes->item(1)->setAttribute("src", "in-plaze/upload/{$value['Image']}");
							if (Session(SESSION_STATUS) == 'login') {
								$elements->item(0)->parentNode->childNodes->item(1)->firstChild->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/ProductEdit/{$value['Product_ID']}'; return false; }");
								$elements->item(0)->parentNode->childNodes->item(1)->firstChild->setAttribute("style", "cursor: pointer;" . $elements->item(0)->parentNode->childNodes->item(1)->firstChild->getAttribute("style"));
							}
							$children[$key] = $elements->item(0)->parentNode->parentNode->cloneNode(true);
						}
						$parent->nodeValue = "";
						foreach ($products as $key => $value) {
							$parent->appendChild($children[$key]);
						}
					}
				}
			}

			if ($_SERVER["SCRIPT_NAME"] == "/category.html" AND !empty($_GET['Category_ID'])) {/* 
				$category = ExecuteRow("SELECT * FROM category WHERE Category_ID = {$_GET['Category_ID']}", 2);
				if (!empty($element = $xpath->query($selector = '//div[contains(@class, "heading-block")]/h2')->item(0)))
					$element->nodeValue = $category['Name'];
				if (!empty($element = $xpath->query($selector = '//div[contains(@class, "heading-block")]/span')->item(0)))
					$element->nodeValue = $category['Name'];
				$elements = $xpath->query($selector = '//div[@id="portfolio"]');
				if (!empty($elements->length)) {
					$children = [];
					$products = ExecuteRows("SELECT * FROM product WHERE Category_ID = {$_GET['Category_ID']} ORDER BY New DESC", 2);
					if (!empty($products)) {
						foreach ($products as $key => $value) {
							$value['Slug'] = slugify($value['Name']);
							$xpath->query('//article/div/a', $elements->item(0))->item(0)->setAttribute("href", "product-{$value['Product_ID']}-{$value['Slug']}");
							$xpath->query('//article/div/a/img', $elements->item(0))->item(0)->setAttribute("src", "in-plaze/upload/{$value['Image']}");
							$xpath->query('//article/div/a/img', $elements->item(0))->item(0)->setAttribute("alt", $value['Name']);
							$xpath->query('//article/div/h3/a', $elements->item(0))->item(0)->nodeValue = $value['Name'];
							$xpath->query('//article/div/h3/a', $elements->item(0))->item(0)->setAttribute("href", "product-{$value['Product_ID']}-{$value['Slug']}");
							if ($xpath->query('//article/div/h3/span', $elements->item(0))->length == 1) {
								$xpath->query('//article/div/h3/span', $elements->item(0))->item(0)->nodeValue = $value['New'] == 1 ? 'New' : '';
								$xpath->query('//article/div/h3/span', $elements->item(0))->item(0)->setAttribute("class", $value['New'] == 1 ? 'leftmargin-sm inline blinking' : '');
							}
							if (($n = $xpath->query($selector = '//article/div/span/a', $elements->item(0))) AND !empty($n->length) AND $n = $n->item(0)) {
								$n->nodeValue = $value['Intro'];
								$n->setAttribute("href", "product-{$value['Product_ID']}-{$value['Slug']}");
							}
							// $xpath->query('//article/div/span/a', $elements->item(0))->item(0)->setAttribute("href", "product-{$value['Product_ID']}-{$value['Slug']}");
							if (Session(SESSION_STATUS) == 'login') {
								$xpath->query('//article/div/a', $elements->item(0))->item(0)->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/ProductEdit/{$value['Product_ID']}'; return false; }");
								$xpath->query('//article/div/a', $elements->item(0))->item(0)->setAttribute("style", "cursor: pointer;" . $xpath->query('//article/div/a', $elements->item(0))->item(0)->getAttribute("style"));
								$xpath->query('//article/div/h3/a', $elements->item(0))->item(0)->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/ProductEdit/{$value['Product_ID']}'; return false; }");
								$xpath->query('//article/div/h3/a', $elements->item(0))->item(0)->setAttribute("style", "cursor: pointer;" . $xpath->query('//article/div/h3/a', $elements->item(0))->item(0)->getAttribute("style"));
								if (($n = $xpath->query($selector = '//article/div/span/a', $elements->item(0))) AND !empty($n->length) AND $n = $n->item(0)) {
									$n->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/ProductEdit/{$value['Product_ID']}'; return false; }");
									$n->setAttribute("style", "cursor: pointer;" . $xpath->query('//article/div/span/a', $elements->item(0))->item(0)->getAttribute("style"));
								}
								// $xpath->query('//article/div/span/a', $elements->item(0))->item(0)->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/ProductEdit/{$value['Product_ID']}'; return false; }");
								// $xpath->query('//article/div/span/a', $elements->item(0))->item(0)->setAttribute("style", "cursor: pointer;" . $xpath->query('//article/div/span/a', $elements->item(0))->item(0)->getAttribute("style"));
							}
							$children[$key] = $xpath->query('//article', $elements->item(0))->item(0)->cloneNode(true);
						}
					}
					while ($elements->item(0)->hasChildNodes())
						$elements->item(0)->removeChild($elements->item(0)->firstChild);
					if (!empty($products)) {
						foreach ($products as $key => $value) {
							$elements->item(0)->appendChild($children[$key]);
						}
					}
				}
			 */}

			if ($template = "Canvas - Blog Detail") {
				// หา tag ใหญ่นอกสุด ที่มี id เป็น blog และมีลูกเป็น single-post (หน้าเนื้อหา)
				if (($parent = $xpath->query($selector = '//*[@class="news-single-post"]')) AND !empty($parent->length)) {
					fb($template, 'template');
					fb($selector, 'selector');
					// เติม attribute data-inplaze ไม่ให้ tag ทั่วไปถูกประมวลผล
					$parent->item(0)->setAttribute("data-inplaze", "true");
					$elements = $parent->item(0);
					$data = ExecuteRow("SELECT *, DATE_FORMAT(Created, '%d %M %Y') AS Blog_Date FROM blog WHERE Blog_ID = {$_GET['Blog_ID']}", 2);
					if (!empty($data)) {
						$site_title = $data['Title'];
						$meta_description = $data['Intro'];
						$og_image = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/' . "in-plaze/upload/{$data['Image']}";
						if (($ns = $xpath->query($selector = './/h1', $parent->item(0))) AND !empty($ns->length))
							$ns->item(0)->nodeValue = $data['Title'];
						if (($ns = $xpath->query($selector = './/h2', $parent->item(0))) AND !empty($ns->length))
							$ns->item(0)->nodeValue = $data['Title'];
						$xpath->query('.//div[contains(@class, "heading-block")]/span', $elements)->item(0)->nodeValue = $data['Blog_Date'];
						$xpath->query('.//img', $elements)->item(0)->setAttribute("src", "in-plaze/upload/{$data['Image']}");
						$html_element = $xpath->query('.//div[contains(@class, "portfolio-single-content")]', $elements);
						if (!empty($data['Content'])) {
							setInnerHTMLP($html_element->item(0), $data['Content']);
						}
					}
					if (Session(SESSION_STATUS) == 'login') {
						foreach ($xpath->query('.//h2', $elements) as $element) {
							$element->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/BlogEdit/{$data['Blog_ID']}'; return false; }");
							$element->setAttribute("style", "cursor: pointer;" . $element->getAttribute("style"));
						}
					}
				}
			}

			if (!'Medium Editor') {
				// $elements = $xpath->query($selector = "//div[not(descendant::div) and descendant::text()]");
				$elements = $xpath->query($selector = "
//div[not(descendant::div) and not(descendant::i) and not(descendant::button) and not(descendant::input) and not(descendant::textarea) and not(descendant::select) and not(descendant::iframe) and not(not(*))]
				");
				if (!empty($elements->length)) {
					foreach ($elements as $key => $element) {
						$pattern = [];
						foreach ($elements->item($key)->childNodes as $child_key => $child_element) {
							$pattern[] = $child_element->nodeName;
						}
						$pattern = implode(',', $pattern);
						$is_global = $elements->item($key)->getAttribute("data-inplaze-global");
						$id = ip_get_element_id($elements->item($key)->nodeValue, $is_global);
						// fb($selector, $id);
						// fb($is_global, $id);
						if (empty($id)) {
							// fb(print_r(array(
							// 	$elements->item($key)->nodeName,
							// 	$elements->item($key)->getAttribute("id"),
							// 	$elements->item($key)->getAttribute("class")
							// ), 1), 'error no id');
						} else {
							fb($pattern, $id);
							$elements->item($key)->setAttribute('editor', 'true');
							$elements->item($key)->setAttribute('data-inplaze-id', $id);
						}
					}
				}

				// ทำ editor ขึ้นมาใหม่ สำหรับ tag ที่เล็กกว่า div แล้วใช้ selector อีกแบบ
			}

			// Link for edit site title, meta description, meta keyword
			if (Session(SESSION_STATUS) == 'login') {
				$elements = $xpath->query($selector = "//footer");
				if (!empty($elements->length)) {
					foreach ($elements as $key => $element) {
						// fb(empty($site_title), 'working');
						if (empty($site_title)) {
							// $site_title = ExecuteScalar("SELECT Value FROM text WHERE Enable = 1 AND Name = 'click-here-to-edit-site-title'");
							$element_div = $dom->createElement('div', 'Site Title: ');
							$element_div->setAttribute("style", "text-align: center;padding-top: 15px;");
							$element_span = $dom->createElement('span', 'click here to edit site title');
							$element_div->appendChild($element_span);
							$elements->item($key)->appendChild($element_div);
						}
						if (empty($meta_description)) {
							// $meta_description = ExecuteScalar("SELECT Value FROM text WHERE Enable = 1 AND Name = 'click-here-to-edit-meta-description'");
							$element_div = $dom->createElement('div', 'Meta Description: ');
							$element_div->setAttribute("style", "text-align: center");
							$element_span = $dom->createElement('span', 'click here to edit meta description');
							$element_div->appendChild($element_span);
							$elements->item($key)->appendChild($element_div);
						}
						if (empty($meta_keyword)) {
							// $meta_keyword = ExecuteScalar("SELECT Value FROM text WHERE Enable = 1 AND Name = 'click-here-to-edit-meta-keyword'");
							$element_div = $dom->createElement('div', 'Meta Keyword: ');
							$element_div->setAttribute("style", "text-align: center;padding-bottom: 15px;");
							$element_span = $dom->createElement('span', 'click here to edit meta keyword');
							$element_div->appendChild($element_span);
							$elements->item($key)->appendChild($element_div);
						}
					}
				}
			}

			// ทำให้ Powered by In-plaze ไม่ถูกประมวลผล ทำให้ไม่สามารถแก้ไขได้
			$parent = $xpath->query($selector = '//div[@class="copyright-links"]');
			if ($parent->length > 0)
				$parent->item(0)->setAttribute("data-inplaze", "true");

			// Link to Backend
			$elements = $xpath->query($selector = "//a[text()='In-plaze' and @href]");
			if (!empty($elements->length)) {
				foreach ($elements as $key => $element) {
					if (Session(SESSION_STATUS) == 'login')
						$elements->item($key)->setAttribute("href", "javascript:alert('ท่านอยู่ในโหมดแก้ไขแล้ว กรุณาคลิ๊กส่วนที่จะแก้ไข')");
					else
						$elements->item($key)->setAttribute("href", "/in-plaze");
				}
			}

			// Image in header and footer and topbar
			$elements = $xpath->query($selector = "//*[ancestor::header]|//*[ancestor::footer]|//*[ancestor::div[@id='top-bar']]");
			if (!empty($elements->length)) {
				foreach ($elements as $key => $element) {
					// fb($elements->item($key)->nodeName);
					$elements->item($key)->setAttribute("data-inplaze-global", "true");
				}
			}

			if ('Edit Primitive Elements by Back-End') {
				// Normal Text
				$elements = $xpath->query($selector = "
					//span[text() and not(ancestor::*[@data-inplaze or (@id='blog' and @data-item='post-item')])]|
					//strong[text() and not(ancestor::*[@data-inplaze or (@id='blog' and @data-item='post-item')])]|
					//small[text() and not(ancestor::*[@data-inplaze or (@id='blog' and @data-item='post-item')])]|
					//abbr[text() and not(ancestor::*[@data-inplaze or (@id='blog' and @data-item='post-item')])]|
					//h1[text() and not(ancestor::*[@data-inplaze or (@id='blog' and @data-item='post-item')])]|
					//h2[text() and not(ancestor::*[@data-inplaze or (@id='blog' and @data-item='post-item')])]|
					//h3[text() and not(ancestor::*[@data-inplaze or (@id='blog' and @data-item='post-item')])]|
					//h4[text() and not(ancestor::*[@data-inplaze or (@id='blog' and @data-item='post-item')])]|
					//h5[text() and not(ancestor::*[@data-inplaze or (@id='blog' and @data-item='post-item')])]|
					//h6[text() and not(ancestor::*[@data-inplaze or (@id='blog' and @data-item='post-item')])]|
					//a[normalize-space(text())!='' and @href!='/in-plaze' and not(ancestor::*[@data-inplaze or (@id='blog' and @data-item='post-item')])]|
					//div[normalize-space(text())!='' and not(ancestor::*[@data-inplaze or (@id='blog' and @data-item='post-item')])]
				");
				if (!empty($elements->length)) {
					foreach ($elements as $key => $element) {
						$text_tag_amount = $elements->item($key)->childNodes->length;
						$pattern = [];
						foreach ($elements->item($key)->childNodes as $child_key => $child_element) {
							$pattern[] = $child_element->nodeName;
						}
						$pattern = implode(',', $pattern);
						$is_global = $elements->item($key)->getAttribute("data-inplaze-global");
						$id = ip_get_element_id($elements->item($key)->nodeValue, $is_global);
						// fb($selector, $id);
						// fb($is_global, $id);
						if ($id == false) {
							fb(print_r(array(
								$elements->item($key)->nodeName,
								$elements->item($key)->getAttribute("id"),
								$elements->item($key)->getAttribute("class")
							), 1), 'error');
						}
						// fb($pattern, $id);
						if ($pattern == "#text" OR $pattern == "#text,br,#text" OR $pattern == "i,#text") {
							// fb($pattern);
							if (!empty($value = ExecuteScalar("SELECT Value FROM text WHERE Enable = 1 AND Name = '{$id}'"))) {
								if ($elements->item($key)->parentNode->nodeName == "a" and substr($elements->item($key)->parentNode->getAttribute("href"), 0, strlen("mailto:")) === "mailto:") {
									// $elements->item($key)->nodeValue = htmlspecialchars_decode(htmlspecialchars($value));
									$elements->item($key)->nodeValue = $value;
									$elements->item($key)->parentNode->setAttribute("href", "mailto:{$value}");
								} else if ($elements->item($key)->parentNode->nodeName == "a" and substr($elements->item($key)->parentNode->getAttribute("href"), 0, strlen("tel:")) === "tel:") {
									// $elements->item($key)->nodeValue = htmlspecialchars_decode(htmlspecialchars($value));
									$elements->item($key)->nodeValue = $value;
									$elements->item($key)->parentNode->setAttribute("href", "tel:{$value}");
								} else if ($elements->item($key)->nodeName == "a" and substr($elements->item($key)->getAttribute("href"), 0, strlen("https://line.me")) === "https://line.me") {
									$elements->item($key)->setAttribute("href", $value);
								} else if ($pattern == "i,#text") {
									// $elements->item($key)->childNodes->item(1)->nodeValue = htmlspecialchars_decode(htmlspecialchars($value));
									$elements->item($key)->childNodes->item(1)->nodeValue = $value;
								} else {
									// fb($value, $id);
									// fb(htmlspecialchars($value), $id);
									// fb(htmlspecialchars_decode($value), $id);
									// $elements->item($key)->nodeValue = htmlspecialchars_decode(htmlspecialchars($value));
									$elements->item($key)->nodeValue = $value;
								}
							}
							if (Session(SESSION_STATUS) == 'login') {
								$elements->item($key)->setAttribute("style", "cursor: pointer;" . $elements->item($key)->getAttribute("style"));
								$is_link = "true";
								if ($elements->item($key)->parentNode->nodeName == "a" or $elements->item($key)->nodeName == "a") {
									$is_link = "confirm('Do you want to edit this?')";
								}
								$elements->item($key)->setAttribute("onclick", "if (window.event.target == this && {$is_link}) { window.location.href = 'in-plaze/TextList?cmd=search&x_Name={$id}'; return false; }");
							}
						} else {
							// fb("{$pattern} for {$selector}", $id, 'exclude_pattern');
							// fb($pattern, 'editor');
							// fb($selector, 'editor');
							// fb($id, 'editor');
							// $elements->item($key)->setAttribute('editor', 'true');
						}
					}
				}

				// Paragraph
				// $elements = $xpath->query($selector = "//p[text() and not(ancestor::*[@data-inplaze or (@id='blog' and @data-item='post-item')])]");
				$elements = $xpath->query($selector = "//p[text() and not(ancestor::*[@html or @data-inplaze or (@id='blog' and @data-item='post-item')])]");
				if (!empty($elements->length)) {
					foreach ($elements as $key => $element) {
						$text_tag_amount = $elements->item($key)->childNodes->length;
						$pattern = [];
						foreach ($elements->item($key)->childNodes as $child_key => $child_element) {
							$pattern[] = $child_element->nodeName;
						}
						$pattern = implode(',', $pattern);
						$is_global = $elements->item($key)->getAttribute("data-inplaze-global");
						$id = ip_get_element_id($elements->item($key)->nodeValue, $is_global);
						// fb($selector, $id);
						// fb($is_global, $id);
						if ($pattern == "#text") {
							if (Session(SESSION_STATUS) == 'login') {
								$elements->item($key)->setAttribute("onclick", "window.location.href = 'in-plaze/ContentsList?cmd=search&x_Name={$id}'; return false;");
								$elements->item($key)->setAttribute("style", "cursor: pointer;" . $elements->item($key)->getAttribute("style"));
							}
							if (!empty($value = ExecuteScalar("SELECT Value FROM contents WHERE Enable = 1 AND Name = '{$id}'"))) {
								// $value = strip_tags($value, get_p_child_tags());
								setInnerHTMLP($elements->item($key), $value);
							}
						} else {
							// fb($pattern, $selector, 'exclude_pattern');
						}
					}
				}

				// HTML in Div
				$elements = $xpath->query($selector = "//div[@html='true']");
				if (!empty($elements->length)) {
					foreach ($elements as $key => $element) {
						$text_tag_amount = $elements->item($key)->childNodes->length;
						// fb($text_tag_amount, $id);
						$pattern = [];
						foreach ($elements->item($key)->childNodes as $child_key => $child_element) {
							$pattern[] = $child_element->nodeName;
						}
						$pattern = implode(',', $pattern);
						// fb($pattern, $id);
						// $is_global = $elements->item($key)->getAttribute("data-inplaze-global");
						$id = ip_get_element_id($elements->item($key)->nodeValue, $is_global);
						// fb($elements->item($key)->nodeValue, $id);
						// fb($selector, $id);
						// fb($is_global, $id);
						// if ($pattern == "#text") {
							if (Session(SESSION_STATUS) == 'login') {
								$elements->item($key)->setAttribute("onclick", "window.location.href = 'in-plaze/ContentsList?cmd=search&x_Name={$id}'; return false;");
								$elements->item($key)->setAttribute("style", "cursor: pointer;" . $elements->item($key)->getAttribute("style"));
							}
							if (!empty($value = ExecuteScalar("SELECT Value FROM contents WHERE Enable = 1 AND Name = '{$id}'"))) {
								// $value = strip_tags($value, get_p_child_tags());
								setInnerHTMLDiv($elements->item($key), $value);
							}
						// } else {
							// fb($pattern, $selector, 'exclude_pattern');
						// }
					}
				}

				// Image
				$elements = $xpath->query($selector = "//img[not(ancestor::*[@data-inplaze or (@id='blog' and @data-item='post-item')])]");
				if (!empty($elements->length)) {
					foreach ($elements as $key => $element) {
						$is_global = $elements->item($key)->getAttribute("data-inplaze-global");
						$id = ip_get_element_id($elements->item($key)->getAttribute("src"), $is_global);
						// fb($selector, $id);
						if (file_exists($elements->item($key)->getAttribute("src")) AND exif_imagetype($elements->item($key)->getAttribute("src")) !== false AND filter_var($elements->item($key)->getAttribute("src"), FILTER_VALIDATE_URL) === false) {
							$image_info = getimagesize(urldecode($elements->item($key)->getAttribute("src")));
							// fb($image_info, 'working');
						}
						if (!empty($value = ExecuteScalar("SELECT Value FROM image WHERE Enable = 1 AND Name = '{$id}'")) AND !empty($value) AND file_exists("in-plaze/upload/{$value}") AND exif_imagetype("in-plaze/upload/{$value}") !== false AND filter_var("in-plaze/upload/{$value}", FILTER_VALIDATE_URL) === false) {
							$elements->item($key)->setAttribute("src", "in-plaze/upload/{$value}");
							if ($elements->item($key)->parentNode->getAttribute("href") != "index.html" AND $elements->item($key)->parentNode->getAttribute("href") != "home.php")
								$elements->item($key)->parentNode->setAttribute("href", "in-plaze/upload/{$value}");
							if ($elements->item($key)->parentNode->getAttribute("data-dark-logo") != "")
								$elements->item($key)->parentNode->setAttribute("data-dark-logo", "in-plaze/upload/{$value}");
							if ($elements->item($key)->parentNode->getAttribute("data-thumb") != "")
								$elements->item($key)->parentNode->setAttribute("data-thumb", "in-plaze/upload/{$value}");
						}
						if (Session(SESSION_STATUS) == 'login') {
							if (!empty($image_info))
								$elements->item($key)->setAttribute("title", "type=\"{$image_info["mime"]}\" {$image_info[3]}");
							$is_link = "true";
							if ($elements->item($key)->parentNode->nodeName == "a") {
								// $elements->item($key)->parentNode->removeAttribute("data-lightbox");
								$is_link = " confirm('Do you want to edit this?')";
							}
							// Portfolio with overlay div on top
							if (
								$elements->item($key)->parentNode->nodeName == 'a'
								AND !empty($elements->item($key)->parentNode->nextSibling->nextSibling)
								AND !empty($elements->item($key)->parentNode->nextSibling->nextSibling->nodeName == 'div')
								AND $elements->item($key)->parentNode->nextSibling->nextSibling->getAttribute('class') == 'portfolio-overlay'
							) {
								$elements->item($key)->parentNode->nextSibling->nextSibling->setAttribute("style", "cursor: pointer");
								$elements->item($key)->parentNode->nextSibling->nextSibling->setAttribute("onclick", "if (window.event.target == this && {$is_link}) { window.location.href = 'in-plaze/ImageList?cmd=search&x_Name={$id}'; return false; }");
							} else if (
								!empty($elements->item($key)->nextSibling) 
								AND $elements->item($key)->nextSibling->nodeName == 'div' 
								AND $elements->item($key)->nextSibling->getAttribute("class") == 'overlay'
							) {
								$elements->item($key)->nextSibling->setAttribute("style", "cursor: pointer");
								$elements->item($key)->nextSibling->setAttribute("onclick", "if (window.event.target == this && {$is_link}) { window.location.href = 'in-plaze/ImageList?cmd=search&x_Name={$id}'; return false; }");
							} else {
								$elements->item($key)->setAttribute("style", "cursor: pointer;" . $elements->item($key)->getAttribute("style"));
								$elements->item($key)->setAttribute("onclick", "if (window.event.target == this && {$is_link}) { window.location.href = 'in-plaze/ImageList?cmd=search&x_Name={$id}'; return false; }");
							}
						}
					}
				}

				// div ที่มีภาพ background
				// $elements = $xpath->query($selector = "//div[contains(@style,'background') AND not(ancestor::*[@data-inplaze])]");
				$elements = $xpath->query($selector = "//div[contains(@style,'background')][not(ancestor::div[@data-inplaze])]");
				if (!empty($elements->length)) {
					foreach ($elements as $key => $element) {
						preg_match_all('/(?<=src=|background=|url\()(\'|")?(?<image>.*?)(?=\1|\))/i', $elements->item($key)->getAttribute("style"), $matches);
						if (!empty($matches['image'][0])) {
							$id = ip_get_element_id($matches['image'][0]);
							if (
								!empty($matches['image'][0]) AND
								file_exists("in-plaze/upload/{$matches['image'][0]}") AND
								exif_imagetype("in-plaze/upload/{$matches['image'][0]}") !== false AND
								filter_var("in-plaze/upload/{$matches['image'][0]}", FILTER_VALIDATE_URL) === false
							) {
								$image_info = getimagesize(urldecode($matches['image'][0]));
								// fb($image_info, 'working');
							}
							if (
								!empty($value = ExecuteScalar("SELECT Value FROM image WHERE Enable = 1 AND Name = '{$id}'")) AND
								file_exists("in-plaze/upload/{$value}")
							) {
								$elements->item($key)->setAttribute("style", str_replace($matches['image'][0], "in-plaze/upload/{$value}", $elements->item($key)->getAttribute("style")));
							}
							if (Session(SESSION_STATUS) == 'login') {
								$elements->item($key)->setAttribute("style", "cursor: pointer;" . $elements->item($key)->getAttribute("style"));
								$elements->item($key)->setAttribute("onclick", "if (window.event.target == this || window.event.target.parentElement == this) { window.location.href = 'in-plaze/ImageList?cmd=search&x_Name={$id}'; return false; }");
								$elements->item($key)->setAttribute("title", "type=\"{$image_info["mime"]}\" {$image_info[3]}");
								if (!empty($elements->item($key)->parentNode->childNodes->item(1)->childNodes) AND ($target = $elements->item($key)->parentNode->childNodes->item(1)->childNodes->item(1)) != NULL) {
									if (strpos($elements->item($key)->parentNode->getAttribute('class'), 'swiper-slide') !== false) {
										$i_node = $dom->createElement("i");
										$i_node->setAttribute("class", "icon-picture");
										$a_node = $dom->createElement("a");
										$a_node->appendChild($i_node);
										$a_node->setAttribute("href", "in-plaze/ImageList?cmd=search&x_Name={$id}");
										$a_node->setAttribute("title", "type=\"{$image_info["mime"]}\" {$image_info[3]}");
										$newnode = $elements->item($key)->parentNode->childNodes->item(1)->childNodes->item(1)->appendChild($a_node);
									}
								}
							}
						}
					}
				}

				// Social Icon
				$elements = $xpath->query($selector = "//a[contains(@class,'social-icon')]/i[@class]|//a/i[contains(@class,'i-circled')]");
				if (!empty($elements->length)) {
					foreach ($elements as $key => $element) {
						$is_global = $elements->item($key)->getAttribute("data-inplaze-global");
						$id = ip_get_element_id($elements->item($key)->getAttribute("class"), $is_global);
						// fb($selector, $id);
						if (!empty($value = ExecuteScalar("SELECT Value FROM text WHERE Enable = 1 AND Name = '{$id}'"))) {
							if (!filter_var($value, FILTER_VALIDATE_EMAIL))
								$elements->item($key)->parentNode->setAttribute("href", $value);
							else
								$elements->item($key)->parentNode->setAttribute("href", "mailto:{$value}");
						}
						if (Session(SESSION_STATUS) == 'login') {
							$elements->item($key)->setAttribute("onclick", "if (window.event.target == this && confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/TextList?cmd=search&x_Name={$id}'; return false; }");
						}
					}
				}

				// iframe from Google Maps, Youtube
				$elements = $xpath->query($selector = "
				//iframe[contains(@src,'https://www.google.com/maps/embed/') and not(ancestor::p)]|
				//iframe[contains(@src,'https://www.youtube.com/embed/') and not(ancestor::p)]
				");
				if (!empty($elements->length)) {
					foreach ($elements as $key => $element) {
						// fb($elements->item($key)->getAttribute("src"));
						$id = ip_get_element_id($elements->item($key)->getAttribute("src"));
						// fb($selector, $id);
						if (!empty($value = ExecuteScalar("SELECT Value FROM text WHERE Enable = 1 AND Name = '{$id}'"))) {
							$elements->item($key)->setAttribute("src", $value);
							// fb($value);
						}
						if (Session(SESSION_STATUS) == 'login') {
							$i_node = $dom->createElement("i");
							$i_node->setAttribute("class", "icon-edit");
							$a_node = $dom->createElement("a");
							$a_node->appendChild($i_node);
							$a_node->setAttribute("href", "in-plaze/TextList?cmd=search&x_Name={$id}");
							$newnode = $elements->item($key)->parentNode->appendChild($a_node);
						}
					}
				}
			}

			if (empty($site_title)) {
				$site_title = ExecuteScalar("SELECT Value FROM text WHERE Enable = 1 AND Name = 'click-here-to-edit-site-title'");
			}

			// Site Title
			$elements = $xpath->query($selector = "//title");
			if (!empty($elements->length)) {
				foreach ($elements as $key => $element) {
					$elements->item($key)->nodeValue = $site_title;
				}
			}

			if (empty($meta_description)) {
				$meta_description = ExecuteScalar("SELECT Value FROM text WHERE Enable = 1 AND Name = 'click-here-to-edit-meta-description'");
			}

			// Meta Description
			$elements = $xpath->query($selector = "//meta[@name='description']");
			if (!empty($elements->length)) {
				foreach ($elements as $key => $element)
					if (!empty($meta_description))
						$elements->item($key)->setAttribute("content", $meta_description);
			} else {
				$elements = $xpath->query($selector = "//head");
				if (!empty($elements->length) AND !empty($meta_description)) {
					foreach ($elements as $key => $element) {
						$element_new = $dom->createElement('meta');
						$element_new->setAttribute("name", "description");
						$element_new->setAttribute("content", $meta_description);
						$elements->item($key)->appendChild($element_new);
					}
				}
			}
		}

		if (empty($meta_keyword))
			$meta_keyword = ExecuteScalar("SELECT Value FROM text WHERE Enable = 1 AND Name = 'click-here-to-edit-meta-keyword'");

		// Meta Keyword
		$elements = $xpath->query($selector = "//meta[@name='keyword']");
		if (!empty($elements->length)) {
			foreach ($elements as $key => $element)
				$elements->item($key)->setAttribute("content", $meta_keyword);
		} else {
			$elements = $xpath->query($selector = "//head");
			if (!empty($elements->length) AND !empty($meta_keyword)) {
				foreach ($elements as $key => $element) {
					$element_new = $dom->createElement('meta');
					$element_new->setAttribute("name", "keyword");
					$element_new->setAttribute("content", $meta_keyword);
					$elements->item($key)->appendChild($element_new);
				}
			}
		}

		// Open Graph Image
		$elements = $xpath->query($selector = "//meta[@property='og:image']");
		if (!empty($elements->length)) {
			foreach ($elements as $key => $element)
				if (!empty($og_image))
					$elements->item($key)->setAttribute("content", $og_image);
		} else {
			$elements = $xpath->query($selector = "//head");
			if (!empty($elements->length) AND !empty($og_image)) {
				foreach ($elements as $key => $element) {
					$element_new = $dom->createElement('meta');
					$element_new->setAttribute("property", "og:image");
					$element_new->setAttribute("content", $og_image);
					$elements->item($key)->appendChild($element_new);
				}
			}
		}

		// Medium Editor
		if (Session(SESSION_STATUS) == 'login') {
			$elements = $xpath->query($selector = "//head");
			if (!empty($elements->length)) {
				foreach ($elements as $key => $element) {
					// fb($element->nodeName);
					$element_new_1 = $dom->createElement('script');
					$element_new_1->setAttribute("src", "//cdn.jsdelivr.net/npm/medium-editor@latest/dist/js/medium-editor.min.js");
					$elements->item($key)->appendChild($element_new_1);
					$element_new_2 = $dom->createElement('link');
					$element_new_2->setAttribute("rel", "stylesheet");
					$element_new_2->setAttribute("href", "//cdn.jsdelivr.net/npm/medium-editor@latest/dist/css/medium-editor.min.css");
					$element_new_2->setAttribute("type", "text/css");
					$element_new_2->setAttribute("media", "screen");
					$element_new_2->setAttribute("charset", "utf-8");
					$elements->item($key)->appendChild($element_new_2);
					$element_new_3 = $dom->createElement('link');
					$element_new_3->setAttribute("rel", "stylesheet");
					$element_new_3->setAttribute("href", "//cdn.jsdelivr.net/npm/medium-editor@latest/dist/css/themes/beagle.css");
					$element_new_3->setAttribute("type", "text/css");
					$element_new_3->setAttribute("media", "screen");
					$element_new_3->setAttribute("charset", "utf-8");
					$elements->item($key)->appendChild($element_new_3);
					$element_new_4 = $dom->createElement('style');
					$element_new_4->nodeValue = '.medium-editor-element { min-height: unset; }';
					$elements->item($key)->appendChild($element_new_4);
				}
			}
			$elements = $xpath->query($selector = "//body");
			if (!empty($elements->length)) {
				foreach ($elements as $key => $element) {
					$element_new_5 = $dom->createElement('script');
					$element_new_5->setAttribute("src", "inc_js.js");
					$elements->item($key)->appendChild($element_new_5);
				}
			}
		}
		echo $dom->saveHTML();
	}
}
