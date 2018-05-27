<?php
if( $basketCol > 0 )
{
    echo '<div class="basket_total_cost"><p>Общая сумма: <span class="basket_total_cost_sum">'.number_format($basketViewResult[0], 0, ',', ' ').'</span></p></div>';
}
?>
<div class="catalog_block">
    <?=$basketViewResult[1]?>
</div>
<script src="public/js/jquery-3.3.1.min.js"></script>
<script src="public/js/basket.js"></script>