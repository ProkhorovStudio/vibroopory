<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
use CNext as Solution;
include('functions.php');

global $USER, $USER_FIELD_MANAGER;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !check_bitrix_sessid() || !$USER->IsAuthorized()) {
	http_response_code(403);
	die();
}

$userId = (int)$USER->GetID();
$commentId = (int)$_POST['commentId'];
$action = (string)($_POST['action'] ?? '');

if ($commentId <= 0 || !in_array($action, ['plus', 'minus'], true)) {
	http_response_code(400);
	die();
}

createField("BLOG_COMMENT", 'UF_ASPRO_COM_LIKE', 'integer');
createField("BLOG_COMMENT_ID", 'UF_LIKE_ID');
createField("BLOG_COMMENT", 'UF_ASPRO_COM_DISLIKE', 'integer');
createField("BLOG_COMMENT_ID", 'UF_DISLIKE_ID');

$ufId = ($userId % 1000).($commentId % 1000);

$fields = $USER_FIELD_MANAGER->GetUserFields("BLOG_COMMENT", $commentId);
$fieldValueId = $USER_FIELD_MANAGER->GetUserFields("BLOG_COMMENT_ID", $ufId);

/* LIKE */
$likeValue = ($fields['UF_ASPRO_COM_LIKE']['VALUE'] ? $fields['UF_ASPRO_COM_LIKE']['VALUE'] : 0);
$likeId = $fieldValueId['UF_LIKE_ID']['VALUE'];
$likeId = Solution::unserialize($likeId);

if( isset($likeId[$userId]) ) {
	$valueLike = $likeId[$userId];
} else {
	$likeId[$userId] = 'N';
	$valueLike = 'N';
}

$bCancelLike = $valueLike == 'Y';
/* LIKE */


/* DISLIKE */
$dislikeValue = ($fields['UF_ASPRO_COM_DISLIKE']['VALUE'] ? $fields['UF_ASPRO_COM_DISLIKE']['VALUE'] : 0);
$dislikeId = $fieldValueId['UF_DISLIKE_ID']['VALUE'];
$dislikeId = Solution::unserialize($dislikeId);

if( isset($dislikeId[$userId]) ) {
	$valueDisLike = $dislikeId[$userId];
} else {
	$dislikeId[$userId] = 'N';
	$valueDisLike = 'N';
}

$bCancelDisLike = $valueDisLike == 'Y';
/* DISLIKE */

if($bCancelLike) {
	$likeId[$userId] = 'N';
	$likeValue--;
	$result["SET_ACTIVE_LIKE"] = false;

	$USER_FIELD_MANAGER->Update("BLOG_COMMENT_ID", $ufId, array('UF_LIKE_ID' => serialize($likeId)));
	$USER_FIELD_MANAGER->Update("BLOG_COMMENT", $commentId, array('UF_ASPRO_COM_LIKE' => $likeValue));
}

if($bCancelDisLike) {
	$dislikeId[$userId] = 'N';
	$dislikeValue--;
	$result["SET_ACTIVE_DISLIKE"] = false;

	$USER_FIELD_MANAGER->Update("BLOG_COMMENT_ID", $ufId, array('UF_DISLIKE_ID' => serialize($dislikeId)));
	$USER_FIELD_MANAGER->Update("BLOG_COMMENT", $commentId, array('UF_ASPRO_COM_DISLIKE' => $dislikeValue));
}



if($action === 'plus') {

	if(!$bCancelLike) {
		$likeId[$userId] = 'Y';
		$likeValue++;
		$result["SET_ACTIVE_LIKE"] = true;

		$USER_FIELD_MANAGER->Update("BLOG_COMMENT_ID", $ufId, array('UF_LIKE_ID' => serialize($likeId)));
		$USER_FIELD_MANAGER->Update("BLOG_COMMENT", $commentId, array('UF_ASPRO_COM_LIKE' => $likeValue));
	}

} else if($action === 'minus') {

	if(!$bCancelDisLike) {
		$dislikeId[$userId] = 'Y';
		$dislikeValue++;
		$result["SET_ACTIVE_DISLIKE"] = true;

		$USER_FIELD_MANAGER->Update("BLOG_COMMENT_ID", $ufId, array('UF_DISLIKE_ID' => serialize($dislikeId)));
		$USER_FIELD_MANAGER->Update("BLOG_COMMENT", $commentId, array('UF_ASPRO_COM_DISLIKE' => $dislikeValue));
	}

}

$result["LIKE"] = intval($likeValue);
$result["DISLIKE"] = intval($dislikeValue);

?>
<?=\Bitrix\Main\Web\Json::encode($result)?>
