<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        p {
            width: 980px;
        }

        p img {
            width: 90%
        }
    </style>
</head>

<body>

    <button id="remove_btn" style="position:fixed">remove</button>

    <div id="willHide">
        <p><img src="https://www.bing.com/th?id=OHR.HappyBalloon_ZH-CN0324866466_UHD.jpg&pid=hp&w=3840&h=2160&rs=1&c=4&r=0" />1</p>
        <p><img src="https://www.bing.com/th?id=OHR.HappyBalloon_ZH-CN0324866466_UHD.jpg&pid=hp&w=3840&h=2160&rs=1&c=4&r=0" />2</p>
        <p><img src="https://www.bing.com/th?id=OHR.HappyBalloon_ZH-CN0324866466_UHD.jpg&pid=hp&w=3840&h=2160&rs=1&c=4&r=0" />3</p>
        <p><img src="https://www.bing.com/th?id=OHR.HappyBalloon_ZH-CN0324866466_UHD.jpg&pid=hp&w=3840&h=2160&rs=1&c=4&r=0" />4</p>
    </div>

    <div>
        <p><img src="https://www.bing.com/th?id=OHR.HappyBalloon_ZH-CN0324866466_UHD.jpg&pid=hp&w=3840&h=2160&rs=1&c=4&r=0" />5</p>
        <p><img src="https://www.bing.com/th?id=OHR.HappyBalloon_ZH-CN0324866466_UHD.jpg&pid=hp&w=3840&h=2160&rs=1&c=4&r=0" />6</p>
        <p><img src="https://www.bing.com/th?id=OHR.HappyBalloon_ZH-CN0324866466_UHD.jpg&pid=hp&w=3840&h=2160&rs=1&c=4&r=0" />7</p>
        <p><img src="https://www.bing.com/th?id=OHR.HappyBalloon_ZH-CN0324866466_UHD.jpg&pid=hp&w=3840&h=2160&rs=1&c=4&r=0" />8</p>
    </div>

    <script>
        document.querySelector('#remove_btn').onclick = function() {
            let last_scroller_positionX = window.scrollX;
            let last_scroller_positionY = window.scrollY;
            document.querySelector('#willHide').style.display = "none";
            window.scrollTo(last_scroller_positionX, last_scroller_positionY)
        }
        window.addEventListener('scroll', function(e) {
            console.log(window.scrollY)
        })
    </script>

</body>

</html>