<?php
session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $id = intval($_SESSION['user']->user_id);
    $theme = ($_SESSION['user']->user_theme == 0) ? 'light' : 'dark';
}

include("users_database.php");
$users = new UsersDatabase();
if (isset($_POST) && $_POST['theme'] != null) {
    $_SESSION['user']->user_theme = intval($_POST['theme']);
    $theme = ($_SESSION['user']->user_theme == 0) ? 'light' : 'dark';
    $user_theme = $users->sanitize($_POST['theme']);
    $user_id = $id;
    $res = $users->changeTheme(
        $user_id,
        $user_theme
    );
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?php echo $theme; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EAgenda - Home</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@1.16.2/dist/full.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2/dist/tailwind.min.css" rel="stylesheet" type="text/css" />
</head>

<body>

    <div class="navbar mb-2 shadow-lg bg-neutral text-neutral-content">
        <div class="px-2 mx-2 navbar-start">
            <span class="text-lg font-bold">
                EAgenda
            </span>
        </div>
        <div class="hidden px-2 mx-2 navbar-center lg:flex">
            <div class="flex items-stretch">
                <a href="index.php" class="btn btn-ghost btn-sm rounded-btn">
                    Home
                </a>
                <a href=<?php echo (isset($_SESSION['user'])) ? "'contacts/contacts_create.php'" : "'login_user.php'"; ?> class="btn btn-ghost btn-sm rounded-btn">
                    <?php echo (isset($_SESSION['user'])) ? "Create" : "Sign In"; ?>
                </a>
                <?php echo !(isset($_SESSION['user'])) ? "
                <a href='create_user.php' class='btn btn-ghost btn-sm rounded-btn'>
                    Sign Up
                </a>" : ""; ?>
            </div>
        </div>
        <div class='navbar-end'>
            <?php $select = "<form method='post' class='mr-4'>
                <select data-theme='dark' class='select select-bordered' name='theme' onchange='if(this.value != null) { this.form.submit(); }'>
                    <option disabled='disabled' selected='selected'>" . strtoupper($theme) . "</option>";
            $select = $theme != 'light' ? $select . '<option value="0">LIGHT</option>' : $select . '<option value="1">DARK</option>';
            $select = $select . "</select></form>";
            echo (isset($_SESSION['user'])) ? $select .
                "<button class='btn btn-square btn-ghost' onclick='location.href=\"logout_user.php\";'>
                <span class='material-icons'>logout</span>
            </button>
            <button class='btn btn-square btn-ghost' onclick='location.href=\"update_user.php\";'>
                <span class='material-icons'>manage_accounts</span>
            </button>
            <div class='flex-none'>
                <div class='avatar'>
                    <div class='rounded-full w-10 h-10 m-1'>
                        <img src='$user->user_image'>
                    </div>
                </div>
            </div>" : ""; ?>
        </div>
    </div>
    <h2 class="flex justify-center mt-6  text-3xl">About</h2>
    <div class="card text-center shadow-2xl">

        <div class="card-body">
            <h2 class="card-title">What is EAgenda?</h2>
            <p>EAgenda is the beginning of a project that seeks to create the most complete electronic agenda and with the greatest amount of functionalities that current technology allows.
                <br>EAgenda is also a free university project.
            </p>
            <div class="justify-center card-actions">

            </div>
        </div>
    </div>

</body>

<footer class="p-10 footer bg-base-200 text-base-content footer-center">
    <div class="grid grid-flow-col gap-4">
        <a href="index.php" class="link link-hover">Home</a>

    </div>

    <div>
        <p>Copyright © 2021 - All right reserved by José Alfú</p>
    </div>
</footer>

</html>