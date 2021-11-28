<?php
session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $id = intval($_SESSION['user']->user_id);
    $theme = ($_SESSION['user']->user_theme == 0) ? 'light' : 'dark';
} else {
    header("location: ../login_user.php");
    exit();
}

include("../users_database.php");
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
                <a href="../index.php" class="btn btn-ghost btn-sm rounded-btn">
                    Home
                </a>
                <a href="contacts_create.php" class="btn btn-ghost btn-sm rounded-btn">
                    Create
                </a>
                <a href="../about.php" class="btn btn-ghost btn-sm rounded-btn">
                    About
                </a>
            </div>
        </div>
        <div class="navbar-end">
            <form method="post" class="mr-4">
                <select data-theme='dark' class="select select-bordered" name="theme" onchange='if(this.value != null) { this.form.submit(); }'>
                    <option disabled="disabled" selected="selected"><?php echo strtoupper($theme); ?></option>
                    <?php echo $theme != "light" ? "<option value='0'>LIGHT</option>" : "<option value='1'>DARK</option>" ?>
                </select>
            </form>
            <button class="btn btn-square btn-ghost" onclick="location.href='../logout_user.php';">
                <span class="material-icons">logout</span>
            </button>
            <button class="btn btn-square btn-ghost" onclick="location.href='../update_user.php';">
                <span class="material-icons">manage_accounts</span>
            </button>
            <div class="flex-none">
                <div class="avatar">
                    <div class="rounded-full w-10 h-10 m-1">
                        <img src="<?php echo $user->user_image; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h2 class="flex justify-center mt-6 mb-4 text-3xl">Contacts</h2>
    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th class="text-center w-0.5">Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php
                include('../contacts_database.php');
                $contacts = new ContactsDatabase();
                $list = $contacts->read($user);
                ?>
                <?php
                while ($row = mysqli_fetch_object($list)) {
                    $id = $row->contact_id;
                    $names = $row->contact_firstname . " " . $row->contact_lastname;
                    $nickname = $row->contact_nickname;
                    $address = $row->contact_address;
                    $email = $row->contact_email;
                    $phone = $row->contact_number;
                    $image = $row->contact_image;
                ?>
                    <tr>

                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="avatar">
                                    <div class="w-12 h-12 mask mask-squircle">
                                        <img src="<?php echo $image; ?>" alt="<?php echo $nickname; ?> avatar">
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">
                                        <?php echo $names; ?>
                                    </div>
                                    <div class="text-sm opacity-50">
                                        <?php echo $nickname; ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php echo $address; ?>

                            <br>
                            <span class="badge badge-outline badge-sm"><?php echo $email; ?></span>
                        </td>
                        <td><?php echo $phone; ?></td>
                        <th>
                            <a href="contacts_update.php?id=<?php echo $id; ?>" class="edit px-2" title="Editar" data-toggle="tooltip"><span class="material-icons">edit</span></a>
                            <a href="contacts_delete.php?id=<?php echo $id; ?>" class="delete px-2" title="Eliminar" data-toggle="tooltip"><span class="material-icons">delete</span></a>
                        </th>
                    </tr>
                <?php
                }
                ?>

            </tbody>
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

        </table>
    </div>

</body>

<footer class="p-10 footer bg-base-200 text-base-content footer-center">
    <div class="grid grid-flow-col gap-4">
        <a href="../about.php" class="link link-hover">About</a>
    </div>
    <div>
        <p>Copyright © 2021 - All right reserved by José Alfú</p>
    </div>
</footer>

</html>