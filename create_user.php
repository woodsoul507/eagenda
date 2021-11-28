<?php
session_start();
if (isset($_SESSION['user'])) {
    header("location: contacts/contacts_list.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EAgenda - Sign Up</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@1.16.2/dist/full.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2/dist/tailwind.min.css" rel="stylesheet" type="text/css" />
</head>

<body>


    <?php
    include "users_database.php";
    $user = new UsersDatabase();
    if (isset($_POST) && !empty($_POST)) {
        if (strcmp($_POST['password'], $_POST['confirm']) == 0) {
            $user_nickname = $user->sanitize($_POST['nickname']);
            $user_name = $user->sanitize($_POST['name']);
            $user_lastname = $user->sanitize($_POST['lastname']);
            $user_password = $user->sanitize($_POST['password']);
            $user_birthday = $user->sanitize($_POST['birthday']);
            $user_number = $user->sanitize($_POST['phone']);
            $user_email = $user->sanitize($_POST['email']);
            $user_address = $user->sanitize($_POST['address']);
            $user_image = $user->sanitize($_POST['image']);
            $res = $user->create(
                $user_nickname,
                $user_name,
                $user_lastname,
                $user_password,
                $user_birthday,
                $user_number,
                $user_email,
                $user_address,
                $user_image
            );
            if ($res) {
                $message = "<div class='alert alert-success'>
            <div class='flex-1'>
              <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' class='w-6 h-6 mx-2 stroke-current'>
                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z'></path>
              </svg>
              <label>User was created!</label>
            </div>
          </div>";
                echo $message;
                header("Location: index.php");
            } else {
                $message = "<div class='alert alert-error'>
            <div class='flex-1'>
              <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' class='w-6 h-6 mx-2 stroke-current'>
                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636'></path>
              </svg>
              <label>Account has been not created!</label>
            </div>
          </div>";
                echo $message;
            }
    ?>
        <?php
        } else {
            $message = "<div class='alert alert-error'>
            <div class='flex-1'>
              <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' class='w-6 h-6 mx-2 stroke-current'>
                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636'></path>
              </svg>
              <label>Passwords must be equal!</label>
            </div>
          </div>";
            echo $message;
        }
        ?>
    <?php
    }
    ?>
    <h2 class="flex justify-center mt-14 mb-4 text-3xl">Sign Up</h2>
    <div class="flex justify-center">
        <form class="w-full max-w-lg" method="POST">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                        First Name
                    </label>
                    <input name="name" required="true" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mb-6" id="grid-first-name" type="text" placeholder="Jacob">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                        Last Name
                    </label>
                    <input name="lastname" required="true" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-last-name" type="text" placeholder="Smith">
                </div>
                <div class="w-full md:w-1/2 px-3 mb-6">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-nickname">
                        Nickname
                    </label>
                    <input name="nickname" required="true" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-nickname" type="text" placeholder="Jack">
                </div>
                <div class="w-full md:w-1/2 px-3 mb-6">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-nickname">
                        Password
                    </label>
                    <input name="password" required="true" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-password" type="password" placeholder="*******">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-phone-number">
                        Phone Number
                    </label>
                    <input name="phone" required="true" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-phone-number" type="tel" placeholder="63782234">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-nickname">
                        Confirm password
                    </label>
                    <input name="confirm" required="true" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-confirm" type="password" placeholder="*******">
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-3">
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-email">
                        Email
                    </label>
                    <input name="email" required="true" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-email" type="email" placeholder="jacob@email.com">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-email">
                        Birthday
                    </label>
                    <input name="birthday" required="true" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-birthday" type="date" max="">
                </div>
            </div>
            <div class=" flex flex-wrap -mx-3 mb-3">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-address">
                        Address
                    </label>
                    <textarea name="address" required="true" class="h-24 appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-address" type="textarea" placeholder="Contact address..."></textarea>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-3">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-image">
                        Image Link
                    </label>
                    <input name="image" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-image" type="text" placeholder="http://contact-image.url/">
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-3">
                <div class="w-full px-3">
                    <input class="btn appearance-none block w-full bg-gray-900 text-white border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 focus:text-gray-700" id="grid-submit" type="submit" value="Create">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <a href="login_user.php" class="px-5 text-lg">I have an account already!</a>
                </div>
                <div class="w-full md:w-1/2 px-3 text-right">
                    <a href="about.php" class="px-5 text-lg">
                        About
                    </a>
                </div>
            </div>

        </form>
    </div>

</body>



</html>