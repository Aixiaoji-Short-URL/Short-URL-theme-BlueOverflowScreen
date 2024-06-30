<?php
include './Configs/LanguagePacks/Engilsh/English-American.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $LanguageV1["title"]; ?></title>
    <link href="./CSS/tailwind.min.css" rel="stylesheet" />
    <style>
        .image-box {
            border-radius: 10px;
            border: 2px solid;
            border-image-slice: 1;
            border-image-source: linear-gradient(to right, #1e3a8a, #2563eb);
            overflow: hidden;
            width: 20%;
            position: absolute;
            top: 50%;
            right: 25%;
            transform: translateY(-50%);
        }

        .image-box img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* 让图片充满容器，保持比例 */
        }

        @media (orientation: portrait) {
            .image-box {
                display: none; /* 竖屏时隐藏图片框 */
            }
        }
    </style>
</head>
<body>

<?php
$output = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 处理表单提交

    $original_url = $_POST["original_url"];
    $password = $_POST["password"];
    $diy_url = $_POST["diy_url"];

    // 获取当前网站域名
    $current_domain = $_SERVER['HTTP_HOST'];

    // 确定协议
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";

    // 构建API请求URL
    $api_url = $protocol . $current_domain . "/addurl_api.php";
    $api_url .= "?original_url=" . urlencode($original_url);

    // 添加密码参数
    if (!empty($password)) {
        $api_url .= "&password=" . urlencode($password);
    }

    // 添加自定义URL参数
    if (!empty($diy_url)) {
        $api_url .= "&diy_url=" . urlencode($diy_url);
    }

    // 使用cURL发送GET请求
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    // 解析JSON响应
    $result = json_decode($response, true);

    // 处理响应
    if ($result["code"] == 1) {
        $output = $LanguageV1["Create-OK"] . "<p><a href='" . $protocol . $current_domain . "/i/?i={$result["diy_url"]}'>" . $protocol . $current_domain . "/i/?i={$result["diy_url"]}</a></p>";
    } else {
        $output = $LanguageV1["Create-ERROR"] . "<p>{$result["msg"]}</p>";
    }
}
?>

<div class="min-h-screen bg-gradient-to-r from-blue-900 to-black flex items-end justify-start p-4 relative">
    <div class="bg-gradient-to-br from-blue-500 to-blue-300 p-6 rounded-xl shadow-lg w-96">
        <div class="text-2xl font-bold bg-gradient-to-r from-zinc-600 to-blue-400 text-white text-center py-2 px-10 rounded-full mb-4 absolute top-4 left-4"><?php echo $LanguageV1["h1"]; ?></div>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-white mb-1"><?php echo $LanguageV1["CC-original_url"]; ?></label>
                <input type="text" name="original_url" class="w-full p-2 rounded-lg bg-gradient-to-r from-gray-300 to-white to-gray-400 text-black" required />
            </div>
            <div>
                <label class="block text-white mb-1"><?php echo $LanguageV1["CC-password"]; ?></label>
                <input type="text" name="password" class="w-full p-2 rounded-lg bg-gradient-to-r from-gray-300 to-white to-gray-400 text-black" />
            </div>
            <div>
                <label class="block text-white mb-1"><?php echo $LanguageV1["CC-diy_url"]; ?></label>
                <input type="text" name="diy_url" class="w-full p-2 rounded-lg bg-gradient-to-r from-gray-300 to-white to-gray-400 text-black" />
            </div>
            <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"><?php echo $LanguageV1["Create-submit"]; ?></button>
        </form>
        <p class="text-white text-center mt-4">Output</p>
        <?php
        if (!empty($output)) {
            echo "<div class='text-white text-center mt-4'>$output</div>";
        }
        ?>
    </div>
    <div class="image-box">
        <img src=".\Configs\Theme\BlueOverflowScreen-ShortURLOfficial-Theme1-BOS\img\HOME.jpg" alt="Your Image">
    </div>
</div>

<script>
    // 检测屏幕方向，并隐藏图片框
    window.addEventListener('load', function () {
        var imageBox = document.querySelector('.image-box');
        if (window.matchMedia("(orientation: portrait)").matches) {
            imageBox.style.display = 'none';
        }
    });
</script>

</body>
</html>
