    <div class="black_bar"></div>
    <div class="wel_top">
    	<div class="container">
    	  <div class="logo">
    	  <?php echo $this->Html->link('The Mall', '/pages/display'); ?></div>
    	　<h1>次世代のオンラインショッピングサイト</h1>
	    	<div class="row">
		    	<div class="span7 wel_top_mockimg">
		    		<?php echo $this->Html->image('welcome_mock.png', array('alt'=>'welcome_mock')); ?>
		    	</div>
		    	<div class="span5">
			    	<div class="wel_top_discription">
				    	<h3>The MallはあなたのFacebookやTwitterの投稿からあなたに最適の商品を推薦します。<br>
					    	全く新しいウィンドウショッピングをお楽しみ下さい。
				    	</h3>
				    	<div class="discription_arrow"></div>
			    	</div>
			    	
			    	<div class="wel_top_social">
			    	
			    	
				    	<a href="<?php echo $this->Html->url('/logins/facebook'); ?>" class="button-social button-facebook"><i class="icon-facebook"></i>Facebookでログイン</a>
				    	
				    	
				    	<a href="<?php echo $this->Html->url('/logins/twitter'); ?>" class="button-social button-twitter"><i class="icon-twitter"></i>Twitterでログイン</a>
			    	</div>
		    	</div>
	    	</div><!-- /row -->
	    
	    </div>
    </div> <!-- /container -->