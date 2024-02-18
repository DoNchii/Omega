<?php

use app\models\Products;
use yii\bootstrap5\Dropdown;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ProductsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css');
$this->title = 'Билет-Постановки';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/css/catalog.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css');
// $this->registerJsFile('@web/js/sort-icons.js');
?>
<div class="products-index">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="card mb-6">
        <div class="card-body bg-gray-100">            
            <div class="dropdown btn btn-primary">
                <a href="#" data-bs-toggle="dropdown" style="text-decoration: none;"
                    class="dropdown-toggle text-white category-filter">Фильтры по категориям<b class="caret"></b></a>
                <?= Dropdown::widget([
                    'items' => [
                        ['label' => 'Все товары', 'url' => '/products/catalog'],
                        ['label' => 'Постановки', 'url' => '/products/catalog?ProductsSearch[category_id]=1'],
                        ['label' => 'Театр', 'url' => '/products/catalog?ProductsSearch[category_id]=2'],
                        ['label' => 'Билет', 'url' => '/products/catalog?ProductsSearch[category_id]=3'],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
    <?php
    $products = $dataProvider->getModels();
    echo "<div class='d-flex flex-row flex-wrap justify-content-center align-items-end'>";
    foreach ($products as $product) {
        if ($product->stock_quantity > 0) {
            echo "<div class='card m-1' style='width: 20%; min-width: 250px;'>
            <a href='/products/view?product_id={$product->product_id}'>
                <img src='{$product->image}' class='card-img-top'
                    style='max-height: 250px;' alt='image'>
            </a>
            <div class='card-body'>
                <h5 class='card-title'>{$product->name}</h5>
                <p class='card-text'>{$product->age}</p>
                <p class='text-danger'>{$product->price} руб</p>";
            echo (Yii::$app->user->isGuest ? "<a href='/products/view?product_id={$product->product_id}' class='btn btn-primary'>Просмотр товара</a>" : "<p onclick='add_product({$product->product_id}, 1)'
                    class='btn btn-primary'>Добавить в корзину</p>");
            echo "
            </div>
        </div>";
        }
    }
    echo "</div>";
    ?>
    <script>
        function add_product(id, items) {
            let form = new FormData();
            form.append('product_id', id);
            form.append('count', items);
            let request_options = { method: 'POST', body: form };
            fetch('https://up-pyatnitca.xn--80ahdri7a.site/cart/create', request_options)
                .then(response => response.text())
                .then(result => {
                    console.log(result)
                    let title = document.getElementById('staticBackdropLabel');
                    let body = document.getElementById('modalBody');
                    let btn = document.getElementById('modalButton');
                    btn.setAttribute('data-bs-dismiss', 'modal')
                    if (result == 'false') {
                        title.innerText = 'Ошибка';
                        body.innerHTML = "<p>Ошибка добавления товара, вероятно, товар уже раскупили</p >"
                    } else {
                        title.innerText = 'Информационное сообщение';
                        body.innerHTML = "<p>Товар успешно добавлен в корзину</p>"
                    }
                    let myModal = new bootstrap.Modal(document.getElementById("staticBackdrop"), {});
                    myModal.show();
                })
        }
    </script>

</div>

