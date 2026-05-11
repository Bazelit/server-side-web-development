<h1><?php echo htmlspecialchars($article['title']); ?></h1>
<p><strong>Автор:</strong> <?php echo htmlspecialchars($authorNickname); ?></p>
<hr>
<div><?php echo nl2br(htmlspecialchars($article['content'])); ?></div>
