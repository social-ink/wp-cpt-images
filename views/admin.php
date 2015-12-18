<?php
global $cptplugin_url_images;
if(!empty($_POST['detach_image']))
	$detached = detach_cpt_image($_POST['cpt_slug']);
elseif(!empty($_POST['cpt_slug']) && !empty($_FILES['ptImages_CPTimage']['tmp_name'])) {
	$img_id = cptImages_savefile($_FILES['ptImages_CPTimage'], $_POST['cpt_label'], $_POST['cpt_slug']);
}	
$links = get_cpt_connections();
	?>
	<div class="wrap">
		<div  id="ptImages" class="metabox-holder">
			<div class="icon32" id="icon-options-general"><br></div>	
			
				<div id="post-body">
					<div id="post-body-content" class="has-sidebar-content">
						<div class="section introsection">
							<h1>WP CPT Images</h1>
							<h3>Add images to custom post types.</h3>	
							<p>To use, select an image for the custom post type and attach.  You can then use the image in various ways:</p>	
							<p><b>As a shortcode</b>.  Put [cptImage] wherever you want the image to appear. Not that in this usage (without a parameter) it will only appear when you are on a single item of that custom post type OR in the archive page itself.  To call any attached image, use [cptImage cpt="Custom Post Type Name"].</p>							
							<p><b>In PHP in your template</b>.  To retrieve the image url to a variable, use <code>get_cptimage_image($optionalname, $echo)</code> (you can use <code>get_cptimage_image("Custom Post Type Name")</code> to retrieve a specific post type image, or <code>get_cptimage_image("Custom Post Type Name", true)</code> to echo the url rather than retrieving it.  To merely display the image, call <code>cptimage_image("Custom Post type Name")</code> or simply <code>cptimage_image()</code>.  Not that if you do not pass a post type name it will only appear when you are on a single item of that custom post type OR in the archive page itself. </p>
							<p>Plugin by yonatan reinberg and the folks at <a href="https://www.social-ink.net">Social Ink</a></p>
						</div>
						<div class="section">
							<h3>all registered post types</h3>										
							<table class="widefat imagetable">
								<thead>
									<tr>
										<th>Post type name</th>
										<th>Post type slug</th>
										<th>Image</th>
										<th>Attach New Image</th>
										<th>Manage Image</th>
									</tr>
								</thead>
								<tbody>
							<?	$allCPTs = get_cpts_images();
								foreach($allCPTs as $CPT) {
									$metainfo = get_post_type_object($CPT);
									$mylabel = $metainfo->label;
									$slug = $metainfo->name;
									$img = array_key_exists($slug,$links) ? $links[$slug] : false;
									if($img) {
										$manage_link = admin_url("upload.php?item=$img");
										$img = reset(wp_get_attachment_image_src($img, 'thumbnail'));
									}
									 ?>
										<tr>
											<td class="labelSection">
												<? echo $mylabel ?>
											</td>
											<td class="labelSection"><? echo $metainfo->name ?></td>
											<td class="labelSection">
												<? if($img) { ?>
													<a target="_blank" title="Manage/Delete Image (will open in new tab)" href="<? echo $manage_link ?>"><img src="<? echo $img ?>" alt=""></a>
													<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
														<input type="hidden" name="cpt_slug" value="<? echo $metainfo->name ?>" />
														<input type="hidden" name="detach_image" value="true" />
														<p class="submit">
															<input type="submit" class="button" name="submit" value="Detach this image" />
														</p>
												<? } else { ?>
													<b>No Image.</b>
												<? } ?>
											</td>
											<td class="labelSection">
												<div class="ptImage_uploadBox imageUploadSection">
													<form method="post" enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
														<input type="file" value=""  class="regular-text code" value="" id="ptImages_CPTimage" name="ptImages_CPTimage">
														<input type="hidden" name="cpt_slug" value="<? echo $metainfo->name ?>" />
														<input type="hidden" name="cpt_label" value="<? echo $mylabel ?>" />
														<p class="submit">
															<input type="submit" class="button-primary" name="submit" value="Upload Selected Image" />
														</p>
													</form>
												</div>																	
											</td>
											<td class="labelSection">
												<? if($img) { ?>
													<a target="_blank" href="<? echo $manage_link ?>">Manage/Delete Image (will open in new tab)</a>
												<? } ?>
											</td>
										</tr>				
									<? 	}	?>		
								</tbody>
								<tfoot>
									<tr>
										<th>Post type name</th>
										<th>Post type slug</th>
										<th>Image</th>
										<th>Attach New Image</th>
										<th>Manage Image</th>								
									</tr>
								</tfoot>									
							</table>
						</div>					
					</div>		
				</div>
			</div>	
 </div>