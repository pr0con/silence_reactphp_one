<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
	</head>
	<style type="text/css">
		.xcss-center {
			width: 100%;
			padding: 15px;
			max-width: 1200px;
			position: relative;
			margin: 0 auto;	
		}
		.xcss-margin-top-10 {
			
		}
	</style>
	<body>
		<div class="xcss-center">
			<label for="form_desc">Description</label>
			<form action="/upload" method="POST" enctype="multipart/form-data">
				<textarea name="form_desc" id="form_desc" ></textarea>
				
				<input type="file" name="form_file" id="form_file" accept="image/x-png,image/jpeg" />
				
				<div class="xcss-margin-top-10"/>
				<button type="submit" class="xbtn xbtn-submit">Submit</button>
			</form>

			<ul class="xcss-list">
				<?php
					$uploads = file('php://stdin');
					
					foreach($uploads as $upload) {
						$upload = trim($upload);
						echo '<li class="xcss-list-element"><img src="thumbnails/'.$upload.'" width="100" height="auto"><a href="/download/'.$upload.'">'.$upload.'</a></li>';
					}
				?>
			</ul>
		</div>
	</body>
</html>