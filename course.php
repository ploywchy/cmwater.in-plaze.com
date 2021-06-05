<?php
namespace PHPMaker2021\inplaze;

$font = 'Kanit:300,400,400i,500';

if ('Remove Element') {/* 
	$remove = [
		"//div[contains(@class, 'advanced-real-estate-tabs')]",
		"//a[i[@class='icon-map-marker2']]",
		"//a[i[@class='icon-line2-list']]",
		"////*[@data-lightbox='gallery']",
		"//a[@title='Like']",
		"//div[contains(@class,'real-estate-item-features')]",
		"//div[contains(@class,'real-estate-item-info')]",
		"//div[contains(@class,'btn-group')]/div[last()]",
		// "//div[contains(@class, 'row')]/div[contains(@class,'col-lg-5') and following-sibling::div[@class='col-lg-3']]",
		"//div[contains(@class,'col-lg-5') and following-sibling::div[@class='col-lg-3']]",
	];
	$nodes = $xpath->query(implode('|', $remove));
	foreach ($nodes as $node) {
		$node->parentNode->removeChild($node);
	}
 */}
/* foreach ($nodes = $xpath->query("//section[@id='slider']") as $node) {
	$class = $node->getAttribute('class');
	$class = RemoveClass($class, 'include-topbar min-vh-60 min-vh-md-100');
	$class = AppendClass($class, 'min-vh-25 min-vh-md-25');
	$node->setAttribute('class', $class);
}
foreach ($nodes = $xpath->query("//div[@id='top-bar']") as $node) {
	$node->setAttribute('style', 'background-color: #2C3E50;');
}
foreach ($nodes = $xpath->query("//div/following-sibling::a[contains(@class, 'button')]") as $node) {
	$node->setAttribute('href', 'listing.html');
} 
foreach ($nodes = $xpath->query("//div[starts-with(@class,'col-lg-4')][descendant::form[@id='quick-contact-form']]") as $node) {
	$class = $node->getAttribute('class');
	$class = RemoveClass($class, 'col-lg-4');
	$class = PrependClass($class, 'col-lg-7');
	$node->setAttribute('class', $class);
} 
foreach ($nodes = $xpath->query("//div[@class='col-lg-3'][following::div[starts-with(@class,'col-lg-')][descendant::form[@id='quick-contact-form']]]") as $node) {
	$node->setAttribute('class', 'col-lg-5');
} 
if (empty($_GET['Category_ID'])) {
	$_GET['Category_ID'] = ExecuteScalar("SELECT Category_ID FROM category LIMIT 1");
}
if (!empty($_GET['Category_ID']) AND ($node = $xpath->query($selector = '//*[@id="dropdownMenu2"]')) AND !empty($node->length) AND $node = $node->item(0)) {
	$node->nodeValue = ExecuteScalar("SELECT Name FROM category WHERE Category_ID = {$_GET['Category_ID']}");
}
if ('index.html : Dynamic Category') {
	if (
		!empty($_GET['Category_ID']) AND
		!empty($datas = ExecuteRows("SELECT *, (SELECT COUNT(*) FROM product WHERE Category_ID = category.Category_ID) AS Total FROM category WHERE (SELECT COUNT(*) FROM product WHERE Category_ID = category.Category_ID) > 0", 2)) AND
		($firstChild = $xpath->query($selector = "//div[contains(@class,'real-estate-properties')]/div[@class='col-lg-4']")) AND
		!empty($firstChild->length)
	) {
		$prototype = $firstChild->item(0)->cloneNode(true);
		$parent = $firstChild->item(0)->parentNode;
		$parent->setAttribute('data-inplaze', true);
		while ($parent->hasChildNodes()) $parent->removeChild($parent->firstChild);
		foreach ($datas as $data) {
			$newNode = $prototype->cloneNode(true);
			$xpath->query('.//h3', $newNode)->item(0)->nodeValue = $data['Name'];
			$xpath->query('.//span', $newNode)->item(0)->nodeValue = $data['Total'];
			preg_match_all('/(?<=src=|background=|url\()(\'|")?(?<image>.*?)(?=\1|\))/i', $xpath->query('.//a', $newNode)->item(0)->getAttribute("style"), $matches);
			if (!empty($matches['image'][0])) {
				if (!empty($matches['image'][0]) AND file_exists("{$matches['image'][0]}") AND exif_imagetype("{$matches['image'][0]}") !== false AND filter_var("{$matches['image'][0]}", FILTER_VALIDATE_URL) === false) {
					$image_info = getimagesize(urldecode($matches['image'][0]));
				}
				$xpath->query('.//a', $newNode)->item(0)->setAttribute("style", str_replace($matches['image'][0], "upload/{$data['Image']}", $xpath->query('.//a', $newNode)->item(0)->getAttribute("style")));
			}
			$xpath->query('.//a', $newNode)->item(0)->setAttribute('href', "listing.html?Category_ID={$data['Category_ID']}");
			if (Session(SESSION_STATUS) == 'login') {
				foreach ($nodes = $xpath->query('.//a', $newNode) as $node) {
					$node->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/CategoryEdit/{$data['Category_ID']}'; return false; }");
				}
			}
			$parent->appendChild($newNode);
		}
	}
}
if ('listing.html : Dynamic Category') {
	if (
		!empty($_GET['Category_ID']) AND
		!empty($datas = ExecuteRows("SELECT category.* FROM category WHERE (SELECT COUNT(*) FROM product WHERE Category_ID = category.Category_ID) > 0 AND Category_ID != {$_GET['Category_ID']}", 2)) AND
		($firstChild = $xpath->query($selector = "//div[contains(@class,'dropdown-menu')]/button")) AND
		!empty($firstChild->length)
	) {
		$prototype = $firstChild->item(0)->cloneNode(true);
		$parent = $firstChild->item(0)->parentNode;
		while ($parent->hasChildNodes()) $parent->removeChild($parent->firstChild);
		$parent->setAttribute('data-inplaze', true);
		foreach ($datas as $data) {
			$newNode = $prototype->cloneNode(true);
			$newNode->nodeValue = $data['Name'];
			$newNode->setAttribute('onclick', "window.location='listing.html?Category_ID={$data['Category_ID']}'");
			$parent->appendChild($newNode);
		}
	}
}
if ('index.html and listing.html : Dynamic Product') {
	if (
		!empty($datas = ExecuteRows("SELECT *, FORMAT(Price, 0) AS Price FROM product WHERE Category_ID = {$_GET['Category_ID']} ORDER BY New DESC", 2)) AND
		($firstChild = $xpath->query($selector = '//div[@class="oc-item"]|//div[contains(@class, "real-estate-item ")]')) AND 
		!empty($firstChild->length)
	) {
		$prototype = $firstChild->item(0)->cloneNode(true);
		$parent = $firstChild->item(0)->parentNode;
		$parent->setAttribute('data-inplaze', true);
		while ($parent->hasChildNodes()) $parent->removeChild($parent->firstChild);
		foreach ($datas as $data) {
			$newNode = $prototype->cloneNode(true);
			$data['Slug'] = slugify($data['Name']);
			$xpath->query('.//div[contains(@class,"real-estate-item-image")]/a/img', $newNode)->item(0)->setAttribute("src", "upload/{$data['Image']}");
			$xpath->query('.//div[contains(@class,"real-estate-item-image")]/a/img', $newNode)->item(0)->setAttribute("alt", $data['Name']);
			if ($xpath->query('.//div[contains(@class,"real-estate-item-image")]/div[contains(@class,"badge")]', $newNode)->length >= 1) {
				if ($data['New'] == 1) {
					$xpath->query('.//div[contains(@class,"real-estate-item-image")]/div[contains(@class,"badge")]', $newNode)->item(0)->nodeValue = $data['New'] == 1 ? 'New' : '';
				} else {
					$xpath->query('.//div[contains(@class,"real-estate-item-image")]/div[contains(@class,"badge")]', $newNode)->item(0)->setAttribute('style', 'display: none');
				}
			}
			$xpath->query('.//div[contains(@class,"real-estate-item-image")]/div[contains(@class,"badge")]', $newNode)->item(0)->setAttribute('style', 'display: none');
			$xpath->query('.//div[contains(@class,"real-estate-item-desc")]/h3/a', $newNode)->item(0)->nodeValue = $data['Name'];
			$xpath->query('.//div[contains(@class,"real-estate-item-desc")]/a', $newNode)->item(0)->setAttribute("style", "display: none;");
			// $xpath->query('.//div[contains(@class,"real-estate-item-desc")]/h3/a', $newNode)->item(0)->setAttribute("href", "product-{$data['Product_ID']}-{$data['Slug']}");
			$xpath->query('.//div[contains(@class,"real-estate-item-desc")]/h3/a', $newNode)->item(0)->setAttribute("href", "single-property.html?Product_ID={$data['Product_ID']}");
			$xpath->query('.//div[contains(@class,"real-estate-item-price")]', $newNode)->item(0)->nodeValue = $data['Price'];
			if (($node = $xpath->query($selector = './/div[contains(@class,"real-estate-item-desc")]/span', $newNode)) AND !empty($node->length) AND $node = $node->item(0)) {
				$node->nodeValue = $data['Intro'];
			}
			if (Session(SESSION_STATUS) == 'login') {
				foreach ($nodes = $xpath->query('.//a', $newNode) as $node) {
					$node->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/ProductEdit/{$data['Product_ID']}'; return false; }");
				}
			}
			$parent->appendChild($newNode);
		}
	}
} */
