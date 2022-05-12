<?php
require_once 'phpQuery/phpQuery/phpQuery.php';
function images()
{
    $model_page_url = file_get_contents($_POST['url']);
    $model_page = phpQuery::newDocument($model_page_url); //Создаём объект страницы библиотекой
    $images_link = $model_page->find('img'); //Ищем все теги img
    foreach ($images_link as $image_link) {
        $images[] = pq($image_link)->attr('src'); //В цикле помещаем ссылку на картинку в массив
    }
    return $images;
}

function val()
{
    return array_chunk(images(), 4); // Разбиваем массив
}

function amount()
{
    return count(images()); // Считаем количество элементов
}

function size()
{
    foreach (images() as $image) {
        if (!empty($image)) {
            $data = get_headers($image, true);
            $size += isset($data['Content-Length']) ? (int)$data['Content-Length'] : 0;

        }
    }
    $size /= 1024;
    $size /= 1024;
    return round($size, 2);
}

?>
<table class="">
    <?php foreach (val() as $row): ?>
        <tr>
            <?php foreach ($row as $image): ?>
                <td><?php echo "<img src='$image'>"; ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>
<div><?php echo amount(); ?></div>
<div><?php echo size(); ?></div>