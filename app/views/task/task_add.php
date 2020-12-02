<article class="app_content container-fluid">
	<div class="row-fluid">
		<div class="col-md-12 span12">
			<h1><?=$page_title;?></h1>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6 offset2">
			<?php if (!empty($msg)):?>
                <div class="alert alert-danger"><?=$msg;?></div>
            <?php endif;?>
			
			<form class="form-horizontal" action="task/add" method="post">
                <div class="control-group">
                    <label class="control-label" for="name">
                        Name<span class="text-error" style="color:red"><b>*</b></span>
                    </label>
                    <div class="controls">
                        <input type="text" name="name" id="name" class="form-control" value="<?=$old["name"] ?? '';?>" required="">
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="email">
                        E-mail<span class="text-error" style="color:red"><b>*</b></span>
                    </label>
                    <div class="controls">
                        <input type="email" name="email" id="email" class="form-control" value="<?=$old["email"] ?? '';?>" required="">
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="text">
                        Task <span class="text-error" style="color:red"><b>*</b></span>
                    </label>
                    <div class="controls">
                        <textarea rows="5" cols="20" id="text" class="form-control input-xxlarge " required="" name="text"><?=$old["text"] ?? '';?></textarea> 
                    </div>
                </div>
		
				<div class="pull-right">
					<input class="btn btn-success" type="submit" value="Add new task">
                    <a class="btn btn-default" href="task/list">Cancel</a>
				</div>
			</form>
		</div>
	</div> <!-- /.row -->
</article>