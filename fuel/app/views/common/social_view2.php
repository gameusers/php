<div class="swiper-container" id="swiper_container_social_button" data-version="2">
	<div class="swiper-wrapper">

		<div class="swiper-slide social_button_box" id="twitter" onclick="GAMEUSERS.common.socialShare(this)"<?php if(AGENT_TYPE) echo 'data-aos="fade-up" data-aos-once="true"'; ?>>
			<img src="<?=URI_BASE?>assets/img/social/social_twitter.png" width="53" height="58">
			<span class="social_button_count font_weight_bold">-</span>
		</div>

		<div class="swiper-slide social_button_box" id="facebook" onclick="GAMEUSERS.common.socialShare(this)"<?php if( ! AGENT_TYPE) echo ' data-aos="fade-up" data-aos-delay="100" data-aos-once="true"'; ?>>
			<img src="<?=URI_BASE?>assets/img/social/social_facebook.png" width="53" height="58">
			<span class="social_button_count font_weight_bold">-</span>
		</div>

		<div class="swiper-slide social_button_box" id="google_plus" onclick="GAMEUSERS.common.socialShare(this)"<?php if( ! AGENT_TYPE) echo ' data-aos="fade-up" data-aos-delay="200" data-aos-once="true"'; ?>>
			<img src="<?=URI_BASE?>assets/img/social/social_google_plus.png" width="53" height="58">
			<span class="social_button_count font_weight_bold">-</span>
		</div>

		<div class="swiper-slide social_button_box" id="hatena" onclick="GAMEUSERS.common.socialShare(this)"<?php if( ! AGENT_TYPE) echo ' data-aos="fade-up" data-aos-delay="300" data-aos-once="true"'; ?>>
			<img src="<?=URI_BASE?>assets/img/social/social_hatena.png" width="53" height="58">
			<span class="social_button_count font_weight_bold">-</span>
		</div>

		<div class="swiper-slide social_button_box" id="pocket" onclick="GAMEUSERS.common.socialShare(this)"<?php if( ! AGENT_TYPE) echo ' data-aos="fade-up" data-aos-delay="400" data-aos-once="true"'; ?>>
			<img src="<?=URI_BASE?>assets/img/social/social_pocket.png" width="53" height="58">
			<span class="social_button_count font_weight_bold">-</span>
		</div>

		<div class="swiper-slide social_button_box" id="line" onclick="GAMEUSERS.common.socialShare(this)"<?php if( ! AGENT_TYPE) echo ' data-aos="fade-up" data-aos-delay="500" data-aos-once="true"'; ?>>
			<img src="<?=URI_BASE?>assets/img/social/social_line.png" width="53" height="58">
			<span class="social_button_count">LINE</span>
		</div>

		<div class="swiper-slide social_button_box" id="email" onclick="GAMEUSERS.common.socialShare(this)"<?php if( ! AGENT_TYPE) echo ' data-aos="fade-up" data-aos-delay="600" data-aos-once="true"'; ?>>
			<img src="<?=URI_BASE?>assets/img/social/social_email.png" width="53" height="58">
			<span class="social_button_count">Mail</span>
		</div>

	</div>
</div>
