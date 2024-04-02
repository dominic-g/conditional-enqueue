<?php
/**
 * Render the tabs settings page.
 */

function conditional_enqueue_render_tabs(){
	?>
<div class="wrapper">
  <div class="title">
    Conditional Enqueue
    <div class="line"></div>
  </div>
  <div class="container">
    <div class="card tabs">
      <input id="tab-1" type="radio" class="tab tab-selector" checked="checked" name="tab" />
      <label for="tab-1" class="tab tab-primary">CSS/JS Selection</label>
      <input id="tab-2" type="radio" class="tab tab-selector" name="tab" />
      <label for="tab-2" class="tab tab-success">Minify</label>
      <input id="tab-3" type="radio" class="tab tab-selector" name="tab" />
      <label for="tab-3" class="tab tab-default">About</label>
      <div class="tabsShadow"></div>
      <div class="glider"></div>
      <section class="content">
        <div class="item" id="content-1">
          <h2 class="tab-title tab-primary">Assets Files Dequeueing</h2>
          <p class="description">
            <span class = "numit">1</span> Select Page you want to change the files queued
          </p>

          <?php
            conditional_enqueue_settings_page_select();
          ?>
        </div>
        <div class="item" id="content-2">
          <h2 class="tab-title tab-success">Tab 2</h2>
          <p>
            <span class = "numit">2</span> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor </p>
        </div>
        <div class="item" id="content-3">
          <h2 class="tab-title tab-default">Tab 3</h2>
          <p>
            <span class = "numit">3</span> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis </p>
        </div>
      </section>

    </div>
  </div>
</div>
	<?php
}