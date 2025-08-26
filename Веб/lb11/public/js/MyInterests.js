function createList(title, elementId, items) {
    const $container = $("#" + elementId);
    if ($container.length === 0) return;

    const $htitle = $("<h3>").text(title);
    const $list = $("<ul>");
    $container.append($htitle);

    items.forEach(item => {
        const $listItem = $("<li>");
        if (typeof item === "object" && item.imgpath && item.text) {
            const $figure = $("<figure>").addClass("my-interests-image");
            const $figcaption = $("<figcaption>").text(item.text);
            const $img = $("<img>").attr("src", item.imgpath).attr("alt", item.text);
            $figure.append($figcaption).append($img);
            $listItem.append($figure);
        } else {
            $listItem.text(item);
        }
        $list.append($listItem);
    });

    $container.append($list);
}
