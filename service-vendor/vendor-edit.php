<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vendor Profile</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: url('https://media.giphy.com/media/BHNfhgU63qrks/giphy.gif') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }


        .profile-edit-container {
            max-width: 2000px;
            padding: 30px;
            margin: 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeIn 1.2s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            text-align: center;
            color: #ffffff;
            font-size: 1.8em;
            margin-bottom: 30px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #e8f0f2;
            margin-bottom: 8px;
            font-size: 0.9em;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea {
            width: 700px;
            padding: 12px;
            font-size: 1em;
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            outline: none;
            backdrop-filter: blur(5px);
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        input:focus,
        textarea:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.5);
        }

        textarea {
            resize: vertical;
        }

        button[type="submit"] {
            display: block;
            width: 100%;
            padding: 12px;
            font-size: 1em;
            font-weight: bold;
            background: linear-gradient(135deg, #4CAF50, #2e7d32);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        button[type="submit"]:hover {
            background: linear-gradient(135deg, #66bb6a, #388e3c);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        }

        /* Profile Picture */
        .profile-pic-preview {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
            position: relative;
        }

        .profile-pic-preview img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 15px rgba(0, 128, 0, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .profile-pic-preview img:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(0, 128, 0, 0.6);
        }

        input[type="file"] {
            margin-top: 10px;
            font-size: 0.9em;
            color: #ffffff;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .profile-edit-container {
                width: 90%;
                padding: 20px;
            }

            button[type="submit"] {
                font-size: 0.9em;
            }

            input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea {
            width: 400px;
            padding: 12px;
            font-size: 1em;
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            outline: none;
            backdrop-filter: blur(5px);
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        }
    </style>
</head>
<body>
    <div class="profile-edit-container">
        <h1>Edit Vendor Profile</h1>
        <form action="update_vendor_profile.php" method="POST" enctype="multipart/form-data">
            <!-- Profile Picture Upload -->
            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <div class="profile-pic-preview">
                    <img id="preview" src="default-profile.png" alt="Profile Picture">
                </div>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewImage(event)">
            </div>
            
            <!-- Name -->
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" value="John Doe" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" value="vendor@example.com" required>
            </div>

            <!-- Phone -->
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" value="123-456-7890" required>
            </div>

            <!-- Address -->
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="4" required>123 Main Street, City, Country</textarea>
            </div>

            <!-- Business Details -->
            <div class="form-group">
                <label for="business_name">Business Name:</label>
                <input type="text" id="business_name" name="business_name" value="Vendor Business" required>
            </div>

            <!-- Submit -->
            <div class="form-group">
                <button type="submit" name="update">Update Profile</button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const preview = document.getElementById('preview');
                preview.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>