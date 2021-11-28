<?php
session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $id = intval($_SESSION['user']->user_id);
    $theme = ($_SESSION['user']->user_theme == 0) ? 'light' : 'dark';
    if (isset($_GET['id'])) {
        $get_contact_id = intval($_GET['id']);
    } else {
        header("location:../index.php");
        exit();
    }
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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EAgenda - Update</title>
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
    <?php
    include("../contacts_database.php");
    $user = $_SESSION['user'];
    $contact_data;
    $contacts = new ContactsDatabase();
    $updateAuth = $contacts->updateAuth($get_contact_id);
    if ($updateAuth) {
        if (isset($_POST) && !empty($_POST['update'])) {
            $contact_nickname = $contacts->sanitize($_POST['nickname']);
            $contact_name = $contacts->sanitize($_POST['firstname']);
            $contact_lastname = $contacts->sanitize($_POST['lastname']);
            $contact_number = $contacts->sanitize($_POST['phone']);
            $contact_email = $contacts->sanitize($_POST['email']);
            $contact_birthday = $contacts->sanitize($_POST['birthday']);
            $contact_address = $contacts->sanitize($_POST['address']);
            $contact_image = $contacts->sanitize($_POST['image']);
            $contact_id = intval($_POST['id_cliente']);
            $res = $contacts->update(
                $contact_nickname,
                $contact_name,
                $contact_lastname,
                $contact_number,
                $contact_email,
                $contact_birthday,
                $contact_address,
                $contact_image,
                $contact_id
            );
            if ($res) {
                $message = "<div class='alert alert-success'>
                <div class='flex-1'>
                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' class='w-6 h-6 mx-2 stroke-current'>          
                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z'></path>                
                </svg> 
                <label>Contact was updated!</label>
                </div>
                </div>";

                echo "<div> $message; </div>";
            } else {
                $message = "<div class='alert alert-error'>
                <div class='flex-1'>
                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' class='w-6 h-6 mx-2 stroke-current'>    
                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636'></path>                      
                </svg> 
                <label>Contact was not updated!</label>
                </div>
                </div>";
                echo "<div> $message; </div>";
            }
    ?>

    <?php
        }
        $contact_data = $contacts->single_record($get_contact_id);
        $dateArray = str_split($contact_data->contact_birthday, 10);
        $dateTime = new DateTime($dateArray[0]);
        $date = $dateTime->format('m-d-Y');
        $response = "<h2 class='flex justify-center mt-14 mb-4 text-3xl'>Updating $contact_data->contact_firstname $contact_data->contact_lastname</h2>
        <div class='flex justify-center'>
            <form class='w-full max-w-lg' method='POST'>
                <div class='flex flex-wrap -mx-3 mb-6'>
                    <div class='w-full md:w-1/2 px-3 mb-6 md:mb-0'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-first-name'>
                            First Name
                        </label>
                        <input name='firstname' class='appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mb-6' id='grid-first-name' type='text' placeholder='$contact_data->contact_firstname' value='$contact_data->contact_firstname'>
                        <input type='hidden' name='id_cliente' id='id_cliente' class='form-control' maxlength='100' value='$contact_data->contact_id'>
    
                    </div>
                    <div class='w-full md:w-1/2 px-3'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-last-name'>
                            Last Name
                        </label>
                        <input name='lastname' class='appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500' id='grid-last-name' type='text' placeholder='$contact_data->contact_lastname' value='$contact_data->contact_lastname'>
                    </div>
                    <div class='w-full md:w-1/2 px-3'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-nickname'>
                            Nickname
                        </label>
                        <input name='nickname' class='appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500' id='grid-nickname' type='text' placeholder='$contact_data->contact_nickname' value='$contact_data->contact_nickname'>
                    </div>
                    <div class='w-full md:w-1/2 px-3'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-phone-number'>
                            Phone Number
                        </label>
                        <input name='phone' class='appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500' id='grid-phone-number' type='tel' placeholder='$contact_data->contact_number' value='$contact_data->contact_number'>
                    </div>
                </div>
                <div class='flex flex-wrap -mx-3 mb-3'>
                    <div class='w-full px-3 md:w-1/2'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-email'>
                            Email
                        </label>
                        <input name='email' class='appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500' id='grid-email' type='email' placeholder='$contact_data->contact_email' value='$contact_data->contact_email'>
                    </div>
                    <div class='w-full px-3 md:w-1/2'>
                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-birthday'>
                        Birthday ($date)
                    </label>
                    <input name='birthday' class='appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500' id='grid-birthday' type='date' max=''>
                </div>
                </div>
                <div class='flex flex-wrap -mx-3 mb-3'>
                    <div class='w-full px-3'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-address'>
                            Address
                        </label>
                        <textarea name='address' class='h-24 appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500' id='grid-address' type='textarea' placeholder='$contact_data->contact_address'>$contact_data->contact_address</textarea>
                    </div>
                </div>
                <div class='flex flex-wrap -mx-3 mb-3'>
                    <div class='w-full px-3'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-image'>
                            Image Link
                        </label>
                        <input name='image' class='appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500' id='grid-image' type='text' placeholder='$contact_data->contact_image' value='$contact_data->contact_image'>
                    </div>
                </div>
                <div class='flex flex-wrap -mx-3 mb-6'>
                    <div class='w-full px-3'>
                        <input class='btn appearance-none block w-full bg-gray-900 text-white border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 focus:text-gray-700' id='grid-submit' type='submit' value='Update' name='update'>
                    </div>
                </div>
    
            </form>
        </div>";
    } else {
        $message = "<div class='alert alert-error'>
        <div class='flex-1'>
          <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' class='w-6 h-6 mx-2 stroke-current'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636'></path>
          </svg>
          <label>You are not allowed to update this contact!</label>
        </div>
      </div>";
        echo "<div> $message; </div>";
    }
    ?>
    <div>
        <?php echo $response; ?>
    </div>

</body>



</html>