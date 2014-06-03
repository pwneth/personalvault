<?php include('connection.php'); 

class michaelNote {
	public function fetch_all_direct() {
		global $pdo;
		
		$query = $pdo->prepare("SELECT * FROM michael_notes ORDER BY note_id DESC");
		$query->execute();
		
		return $query->fetchAll();
	}
}

function make_links_clickable($text){
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $text);
}

//======================== START OF FUNCTION ==========================//
// FUNCTION: bbcode_to_html                                            //
//=====================================================================//
function bbcode_to_html($bbtext){
  $bbtags = array(
    '[heading1]' => '<h1>','[/heading1]' => '</h1>',
    '[heading2]' => '<h2>','[/heading2]' => '</h2>',
    '[heading3]' => '<h3>','[/heading3]' => '</h3>',
    '[h1]' => '<h1>','[/h1]' => '</h1>',
    '[h2]' => '<h2>','[/h2]' => '</h2>',
    '[h3]' => '<h3>','[/h3]' => '</h3>',

    '[paragraph]' => '<p>','[/paragraph]' => '</p>',
    '[para]' => '<p>','[/para]' => '</p>',
    '[p]' => '<p>','[/p]' => '</p>',
    '[left]' => '<p style="text-align:left;">','[/left]' => '</p>',
    '[right]' => '<p style="text-align:right;">','[/right]' => '</p>',
    '[center]' => '<p style="text-align:center;">','[/center]' => '</p>',
    '[justify]' => '<p style="text-align:justify;">','[/justify]' => '</p>',

    '[bold]' => '<span style="font-weight:bold;">','[/bold]' => '</span>',
    '[italic]' => '<span style="font-weight:bold;">','[/italic]' => '</span>',
    '[underline]' => '<span style="text-decoration:underline;">','[/underline]' => '</span>',
    '[b]' => '<span style="font-weight:bold;">','[/b]' => '</span>',
    '[i]' => '<span style="font-weight:bold;">','[/i]' => '</span>',
    '[u]' => '<span style="text-decoration:underline;">','[/u]' => '</span>',
    '[break]' => '<br>',
    '[br]' => '<br>',
    '[newline]' => '<br>',
    '[nl]' => '<br>',
    
    '[unordered_list]' => '<ul>','[/unordered_list]' => '</ul>',
    '[list]' => '<ul>','[/list]' => '</ul>',
    '[ul]' => '<ul>','[/ul]' => '</ul>',

    '[ordered_list]' => '<ol>','[/ordered_list]' => '</ol>',
    '[ol]' => '<ol>','[/ol]' => '</ol>',
    '[list_item]' => '<li>','[/list_item]' => '</li>',
    '[li]' => '<li>','[/li]' => '</li>',
    
    '[*]' => '<li>','[/*]' => '</li>',
    '[code]' => '<code>','[/code]' => '</code>',
    '[preformatted]' => '<pre>','[/preformatted]' => '</pre>',
    '[pre]' => '<pre>','[/pre]' => '</pre>',     
  );

  $bbtext = str_ireplace(array_keys($bbtags), array_values($bbtags), $bbtext);

  $bbextended = array(
    "/\[url](.*?)\[\/url]/i" => "<a href=\"http://$1\" title=\"$1\">$1</a>",
    "/\[url=(.*?)\](.*?)\[\/url\]/i" => "<a href=\"$1\" title=\"$1\">$2</a>",
    "/\[email=(.*?)\](.*?)\[\/email\]/i" => "<a href=\"mailto:$1\">$2</a>",
    "/\[mail=(.*?)\](.*?)\[\/mail\]/i" => "<a href=\"mailto:$1\">$2</a>",
    "/\[img\]([^[]*)\[\/img\]/i" => "<img src=\"$1\" alt=\" \" />",
    "/\[image\]([^[]*)\[\/image\]/i" => "<img src=\"$1\" alt=\" \" />",
    "/\[image_left\]([^[]*)\[\/image_left\]/i" => "<img src=\"$1\" alt=\" \" class=\"img_left\" />",
    "/\[image_right\]([^[]*)\[\/image_right\]/i" => "<img src=\"$1\" alt=\" \" class=\"img_right\" />",
  );

  foreach($bbextended as $match=>$replacement){
    $bbtext = preg_replace($match, $replacement, $bbtext);
  }
  return $bbtext;
}
//=====================================================================//
//  FUNCTION: bbcode_to_html                                           //
//========================= END OF FUNCTION ===========================//

$michael_note = new michaelNote;
$michael_notes = $michael_note->fetch_all_direct();

/*if (strpos($_POST['note_content'],'>') !== false || strpos($_POST['note_content'],'<') !== false) {
	$errors = true;
};*/

if (isset($_POST['note_content']) && ($errors == false))
	{
	$note_content = $_POST['note_content'];
	$note_user = $_COOKIE['user_name'];
	$date = date("F j, Y, g:i a");
	
	$query = $pdo->prepare('INSERT INTO michael_notes (note_content, note_timestamp, note_user) VALUES (:note_content, :note_timestamp, :note_user)');
						
	$query->execute(array(':note_content'=>$note_content,
						  ':note_user'=>$note_user,
						  ':note_timestamp'=>$date));
	
	header('Location: /[**folder_location**]');
	}
	
if (isset($_POST['username']) && isset($_POST['password'])) {
	if ((($_POST['username'] == '[**your_username**]') && ($_POST['password'] == '[**your_password**]'))) {
		$username = $_POST['username'];
		$correct_password = true;
		setcookie("correct_password", $correct_password);
		setcookie("user_name", $username);
		header('Location: /[**folder_location**]');
	} else {
		$correct_password = NULL;
	}
	
}


if (isset($_POST['note_id'])) {
	
	$sql = "DELETE FROM michael_notes WHERE note_id =  :note_id";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':note_id', $_POST['note_id'], PDO::PARAM_INT);  
	$stmt->execute();
	
	header('Location: /[**folder_location**]');
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>My Vault :)</title>
	<link rel='stylesheet' href='style.css' >
	<link href='http://fonts.googleapis.com/css?family=Alegreya+Sans+SC:100' rel='stylesheet' type='text/css'>
	<script src="jquery.min.js"></script>
	<script src="jquery-ui.min.js"></script>
	<script src="note.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

<?php if (isset($_COOKIE["correct_password"])) { ?>

	<form method='post' action=''>
		<textarea placeholder="<?php if ($errors == true) { echo "Please use BBC Code. ([img]http://example.com/example.jpg[/img])"; } else { echo "Enter your note here."; } ?>" name='note_content' required></textarea>
		<input type='submit' value='Add Note' >
	</form>

	<div id="add_note_btn" <?php if ($errors == true) { echo "style=\"background-color: red;\""; } ?>><?php if ($errors == true) { echo "Please use BBC Code."; } else { echo "Add Note"; } ?></div>
	<div id="notes">
	<?php

	foreach ($michael_notes as $michael_note) { ?>
	<div class="note">
		<div class="note_date"><?php echo $michael_note['note_timestamp']; ?> by: <?php echo $michael_note['note_user']; ?></div>
		<div class="note_content"><?php echo $michael_note['note_content']; ?></div>
		
		<?php if ($michael_note['note_user'] == $_COOKIE['user_name']) { ?>
		
		<div class="note_delete">
			<form method='post' action='' onsubmit="return confirm('Are you sure you want to delete this post?');">
				<input type="hidden" name="note_id" value="<? echo $michael_note['note_id']; ?>"/>
				<input type='submit' value='x' >
			</form>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
	</div>
	<div id="footer">
		Logged in as: <?php echo $_COOKIE['user_name']; ?>. <a href="logout.php">Logout</a>
	</div>
	
	<div id="copy">
		&copy; 2014 - michaelpnavarro.com 
	</div>

<?php } else { ?>

	<div id="password">
		<form method='post' action=''>
			<input type='text' name='username' placeholder='User Name' required>
			<input type='password' name='password' placeholder='Password' required>
			<input type='submit' value='Submit' >
		</form>
		<?php 
		if (isset($_POST['username']) && isset($_POST['password'])) {
			if ($correct_password !== true) { ?>
			<div class="error">Username / Password Incorrect.</div>
			<?php }
		}	
		?>
	</div>
	
<?php } ?>

	

</body>

</html>



