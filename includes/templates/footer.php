		<script src="admin/layout/js/jquery-3.5.1.min.js"></script>
		<script src="admin/layout/js/jquery-ui.min.js"></script>
    <script src="admin/layout/js/popper.min.js"></script>
		<script src="admin/layout/js/bootstrap.min.js"></script>
		<script src="admin/layout/js/jquery.selectBoxIt.min.js"></script>
    <script src="<?php echo $js ?>swiper.min.js"></script>
    <script src="<?php echo $js ?>indexs.js"></script>
    <script src="<?php echo $js ?>countdown.js"></script>
    <script>
      var swiper = new Swiper('.swiper-container', {
        effect: 'coverflow',
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: 'auto',
        coverflowEffect: {
          rotate: 50,
          stretch: 0,
          depth: 100,
          modifier: 1,
          slideShadows : true,
        },
        pagination: {
          el: '.swiper-pagination',
        },
      });
  </script>
	</body>
</html>