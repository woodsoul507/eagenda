<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EAgenda - Create</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@1.16.2/dist/full.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2/dist/tailwind.min.css" rel="stylesheet" type="text/css" />
</head>

<body>

    <?php
    include "users_database.php";
    $user = new UsersDatabase();
    if (isset($_POST) && !empty($_POST)) {
        $user_password = $user->sanitize($_POST['password']);
        $user_email = $user->sanitize($_POST['email']);
        $res = $user->login(
            $user_password,
            $user_email
        );
        if ($res) {
            $message = "<div class='alert alert-success'>
            <div class='flex-1'>
              <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' class='w-6 h-6 mx-2 stroke-current'>
                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z'></path>
              </svg>
              <label>You are in!</label>
            </div>
          </div>";
            header("location: contacts/contacts_list.php");
        } else {
            $message = "<div class='alert alert-error'>
            <div class='flex-1'>
              <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' class='w-6 h-6 mx-2 stroke-current'>
                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636'></path>
              </svg>
              <label>Login fail. Email and password mismatch!</label>
            </div>
          </div>";
        }
    ?>
        <div>
            <?php echo $message; ?>
        </div>
    <?php
    }
    ?>
    <div class="flex items-center justify-center min-h-screen w-full pb-40">

        <div class="flex justify-center">
            <form class="w-full max-w-lg" method="POST">
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="flex justify-center mb-4 text-3xl w-full">
                        <h2 class="text-3xl">Sign In</h2>
                    </div>

                    <div class="w-full md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-email">
                            Email
                        </label>
                        <input name="email" required="true" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-email" type="email" placeholder="jacob@email.com">
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-password">
                            Password
                        </label>
                        <input name="password" required="true" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-password" type="password" placeholder="*******">
                    </div>
                    <div class="w-full px-3">
                        <input class="btn appearance-none block w-full bg-gray-900 text-white border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 focus:text-gray-700" id="grid-submit" type="submit" value="Submit">
                    </div>
                    <a href="create_user.php" class="px-5 text-lg">Create new account!</a>
                </div>

            </form>
        </div>

    </div>

</body>

</html>