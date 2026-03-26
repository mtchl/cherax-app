<?php
$imagePath = isset($_GET['path']) ? $_GET['path'] : '';
$tags = isset($_GET['tag']) && is_array($_GET['tag']) ? $_GET['tag'] : [];

$baseDir = realpath(__DIR__);
$requestedFile = realpath($baseDir . DIRECTORY_SEPARATOR . $imagePath);

$isValid = false;
$fileType = ''; // Will be 'image' or 'video'
$metadata = [
    'camera' => 'Unknown',
    'date'   => 'Unknown Date',
    'time'   => 'Unknown Time'
];

if ($requestedFile && strpos($requestedFile, $baseDir) === 0 && is_file($requestedFile)) {
    $ext = strtolower(pathinfo($requestedFile, PATHINFO_EXTENSION));
    $filename = pathinfo($requestedFile, PATHINFO_FILENAME);

    // Define supported formats
    $imageExts = ['jpg', 'jpeg', 'png', 'webp'];
    $videoExts = ['mp4', 'webm', 'ogg'];

    if (in_array($ext, $imageExts)) {
        $isValid = true;
        $fileType = 'image';
    } elseif (in_array($ext, $videoExts)) {
        $isValid = true;
        $fileType = 'video';
    }

    if ($isValid) {
        // Parse filename: camD-20241122-060704
        $parts = explode('-', $filename);
        if (count($parts) >= 3) {
            $metadata['camera'] = htmlspecialchars(str_replace('cam','',$parts[0]));
            
            $dateObj = DateTime::createFromFormat('Ymd', $parts[1]);
            if ($dateObj) $metadata['date'] = $dateObj->format('d/m/Y');

            $timeObj = DateTime::createFromFormat('His', $parts[2]);
            if ($timeObj) $metadata['time'] = $timeObj->format('g:i:s A');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mosaic: Life on the Western Lakes - Capture <?php echo htmlspecialchars($imagePath); ?></title>
    <link rel="icon" href="favicon.svg" sizes="any" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..800&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style lang="css">

    html, body{
        font-family: 'Inconsolata', monospace;
        min-height:1200px;
    }

    a{
        color:black;
        text-decoration: none;
    }

    .item{
        flex:1;
        max-width: 900px;
        min-width:640px;
        margin-bottom:1rem;
        background-color: white;
        padding:1rem;
      }

      .item.modal{
    /*    height: 100%;*/
        aspect-ratio: 1.32;
        width: auto;
        max-height: 80%;
        max-width: 100%;
        flex: 1;
        flex-grow: 0;
        margin-bottom: 0;
        min-width: unset;
        position: relative;
        box-sizing: border-box;
      }

    /*  .item.modal video{
        padding:0;
      }*/

      .item img, .item video {
        width:100%;
      }

      .item img{
        aspect-ratio: 4 / 2.8;
        object-fit: cover;
      }

      .item.modal .closebutton{
        width:2rem;
        height:2rem;
        position:absolute;
        top:0;
        right:0;
        background-color: white;
        
      }

      .item.modal .closebutton img{
        width:1.5rem;
        height:1.5rem;
        margin:0.25rem;
        cursor: pointer;
      }

      .item.modal .closebutton img:hover{
        transform-origin: center;
        transform: scale(1.1);
      }

      .item video{
        padding:6.9% 0;
        background-color: black;
      }

      .item p{
        margin:0.25rem;
      }

      .metadata{
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: space-between;
      }

      .metadata .col.left{
        margin: 0;
      }

      .metadata .captureTime {
        display:inline-block;
        margin: 0 0.5rem;
      }

      .metadata .col.right{
        margin-top:0.25rem;
        text-align: right;
      }

      .itemTag{
        font-size: 80%;
        display: inline-block;
        margin:0.25rem 0.25rem 0 0;
        background-color: #ddd;
        padding:0.25rem 0.5rem;
        cursor: pointer;
        border-radius:0.75em;
        border:1px solid transparent;
      }

      .itemTag.active{
        background-color: #222;
        color: white;
      }

      .itemTag:hover{
        background-color: #bbb;
      }

      button.icon{
        display: inline-block;
        background-color: transparent;
        border: none;
        padding: 0; 
        cursor: pointer; 
        width:1.9rem;
    /*    height:1.5rem;*/
    /*    overflow: visible;*/
        margin-right:0.1rem;
        position:relative;
        top:0.35rem;
        opacity:0.25;
    /*    vertical-align: middle;*/
      }

      button.share{
        top:0.20rem;
        width:1.8rem;
      }

      button.icon img{
        display: block;
        width:100%;
        object-fit: contain;
      }

      button.icon:hover{
        opacity:0.8;
      }

      .sharedModal{
        position:fixed;
        top:0;
        left:0;
        width:100%;
        height:100%;
        background-color: rgba(0,0,0,0.8);
        z-index: 100;
      }

     .sharedModal .inner{
        margin:5vh auto ;

        height:90vh;
        width: 90vw;
    /*    width:90%;*/
        display: flex;
        justify-content: center; 
        align-items: center; 
        flex-direction: column;
        flex-wrap: nowrap ;
      }

      .shareIntro{
        flex:1;
        background: rgba(226, 227, 216, 1);
        padding:0.5rem 3rem 1rem;
        text-align: center;
        border-radius: 1.5rem;
        margin-top:1rem;
        flex-grow: 0;

      }

      .shareIntro img{
        width:13rem;
      }

      .shareIntro p{
        font-family: Lato, sans-serif;
        margin:0.5rem 0;
      }

      @media screen and (width < 600px) {

        html{
        font-size:14px;
        }

        .item{
          min-width:320px;
          margin-bottom:1rem;
          padding:0.5rem;
        }

        .item.modal{
          width:100%;
          aspect-ratio: unset;
          height:auto;
        }

        .metadata{
          font-size:90%;
        }
      }

      @media screen and (width < 360px) {
          html{
            font-size:13px;
          }
       }

</style>
</head>
<body>

<?php if ($isValid): ?>
    <div class="sharedModal">
        <div class="inner">

            <div class="item modal">

                <?php if ($fileType === 'image'): ?>
                    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Camera trap image">
                <?php else: ?>
                    <video controls playsinline>
                        <source src="<?php echo htmlspecialchars($imagePath); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php endif; ?>

                 <div class="metadata">
                    <div class="col left">
                        <a href="https://media.flow-mer.org.au/mosaic/captures?cam=<?php echo $metadata['camera']; ?>" class="itemTag cam" title="Browse captures from Cam <?php echo $metadata['camera']; ?>">Cam <?php echo $metadata['camera']; ?></a>
                       <span class="captureTime"> <?php echo $metadata['date'] . ', ' . $metadata['time']; ?> </span>
                    </div>
                    <div class="col right">
                        <?php if (!empty($tags)): ?>
                            <?php foreach ($tags as $tag): ?>
                                <a class="itemTag" href="https://media.flow-mer.org.au/mosaic/captures?species=<?php 
                                $linktag = strtolower($tag);
                                $linktag = str_replace(' ','-',$linktag);
                                echo htmlspecialchars($linktag);
                                ?>">
                                <?php echo htmlspecialchars($tag);?>    
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                 </div>

            </div>
            
            <div class="shareIntro">
                <p>This is just part of the picture. Explore:</p>
                <a href="https://media.flow-mer.org.au/mosaic/"><img src="logo-lockup.svg"/></a>
            </div>

        </div>
    </div>


    <?php else: ?>
        <div class="error">
            <h1>File Not Found or Unsupported</h1>
            <p>The path <strong><?php echo htmlspecialchars($imagePath); ?></strong> is not a valid media file.</p>
        </div>
    <?php endif; ?>

</body>



</html>