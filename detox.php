<?php
namespace PHPMaker2021\inplaze;

if ($template = 'articles.html and csr.html : First Blog') {
	if (
		($parent = $xpath->query($selector = '//div[@id="first-post"]/div[contains(@class, "entry")]')) AND
		!empty($parent->length) AND
		(($data_inplaze_tag = $parent->item(0)->parentNode->getAttribute("data-inplaze-tag")) OR !empty($data_inplaze_tag = ExecuteScalar("SELECT * FROM tag"))) AND
		!empty($data = ExecuteRow("SELECT *, DATE_FORMAT(Created, '%d %M %Y') AS Blog_Date FROM blog WHERE FIND_IN_SET({$data_inplaze_tag}, Tags) ORDER BY Blog_ID DESC, Blog_ID DESC LIMIT 1", 2))
	) {
		$parent = $parent->item(0);
		$parent->setAttribute('data-inplaze', true);
		$data['Slug'] = slugify($data['Title']);
		foreach ($ns = $xpath->query('.//img', $parent) as $n) { $n->setAttribute("src", "in-plaze/upload/{$data['Image']}"); }
		foreach ($ns = $xpath->query('.//img', $parent) as $n) { $n->setAttribute("alt", $data['Title']); }
		foreach ($ns = $xpath->query('.//a', $parent) as $n) { $n->setAttribute("href", "blog-{$data['Blog_ID']}-{$data['Slug']}"); }
		foreach ($ns = $xpath->query('.//a', $parent) as $n) { $n->setAttribute("href", "blog-{$data['Blog_ID']}-{$data['Slug']}"); }
		foreach ($ns = $xpath->query('.//*[contains(@class,"entry-meta")]//li/text()', $parent) as $n) { $n->nodeValue = $data['Blog_Date']; }
		foreach ($ns = $xpath->query('.//div[contains(@class,"entry-title")]//a', $parent) as $n) { $n->nodeValue = $data['Title']; }
		foreach ($ns = $xpath->query('.//p', $parent) as $n) { setInnerHTML($n, empty($data['Intro']) ? ' ' : nl2br($data['Intro'])); }
		if (Session(SESSION_STATUS) == 'login') {
			foreach ($ns = $xpath->query('.//a|.//h2|.//p', $parent) as $n) {
				$n->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/BlogEdit/{$data['Blog_ID']}'; return false; }");
				$n->setAttribute("style", "cursor: pointer;");
			}
		}
	}
}

if ($template = 'articles.html and csr.html : Other Blogs (Posts)') {
	if (
		($firstChild = $xpath->query($selector = '//div[@id="other-posts"]/div[contains(@class, "entry")]')) AND
		!empty($firstChild->length) AND
		(($data_inplaze_tag = $firstChild->item(0)->parentNode->getAttribute("data-inplaze-tag")) OR !empty($data_inplaze_tag = ExecuteScalar("SELECT * FROM tag"))) AND
		!empty($datas = ExecuteRows("SELECT *, DATE_FORMAT(Created, '%d %M %Y') AS Blog_Date FROM blog WHERE FIND_IN_SET({$data_inplaze_tag}, Tags) ORDER BY Blog_ID DESC, Blog_ID DESC LIMIT 1, 999", 2))
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
			foreach ($ns = $xpath->query('.//a', $newNode) as $n) { $n->setAttribute("href", "blog-{$data['Blog_ID']}-{$data['Slug']}"); }
			foreach ($ns = $xpath->query('.//a', $newNode) as $n) { $n->setAttribute("href", "blog-{$data['Blog_ID']}-{$data['Slug']}"); }
			foreach ($ns = $xpath->query('.//*[contains(@class,"entry-meta")]//li/text()', $newNode) as $n) { $n->nodeValue = $data['Blog_Date']; }
			foreach ($ns = $xpath->query('.//div[contains(@class,"entry-title")]//a', $newNode) as $n) { $n->nodeValue = $data['Title']; }
			foreach ($ns = $xpath->query('.//p', $newNode) as $n) { setInnerHTML($n, empty($data['Intro']) ? ' ' : nl2br($data['Intro'])); }
			if (Session(SESSION_STATUS) == 'login') {
				foreach ($ns = $xpath->query('.//a|.//h2|.//p', $n) as $n) {
					$n->setAttribute("onclick", "if (confirm('Do you want to edit this?')) { window.location.href = 'in-plaze/BlogEdit/{$data['Blog_ID']}'; return false; }");
					$n->setAttribute("style", "cursor: pointer;");
				}
			}
				$parent->appendChild($newNode);
		}
	}
}
