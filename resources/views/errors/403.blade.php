<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>403</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .custom-error-page {
            width: 100vw;
            height: 100vh;
            display: flex;
            background: #fff;
        }

        .custom-error-image {
            width: 70%;
            height: 100vh;
            overflow: hidden;
        }

        .custom-error-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .custom-error-actions {
            width: 30%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 15px;
            padding: 30px;
        }

        .custom-error-actions .custom-home-btn,
        .custom-error-actions .custom-back-btn {
            width: 180px;
            padding: 12px 20px;
            font-size: 15px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
        }

        .custom-home-btn {
            color: #fff;
            background-color: #b8825e;
            border: 1px solid #b8825e;
        }

        .custom-home-btn:hover,
        .custom-home-btn:focus,
        .custom-home-btn:active {
            color: #fff;
            background-color: #b8825e;
            border-color: #b8825e;
        }

        .custom-back-btn {
            color: #fff;
            background-color: #6c757d;
            border: 1px solid #6c757d;
        }

        .custom-back-btn:hover,
        .custom-back-btn:focus,
        .custom-back-btn:active {
            color: #fff;
            background-color: #5a6268;
            border-color: #545b62;
        }

        @media (max-width: 768px) {
            .custom-error-page {
                flex-direction: column;
            }

            .custom-error-image {
                width: 100%;
                height: 75vh;
            }

            .custom-error-actions {
                width: 100%;
                height: 25vh;
                flex-direction: row;
                padding: 15px;
                gap: 10px;
            }
        }

        @media (max-width: 480px) {
            .custom-error-image {
                height: 70vh;
            }

            .custom-error-actions {
                height: 30vh;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <div class="custom-error-page">

        <div class="custom-error-image">
            <img src="{{ asset('assets/img/errors/403.png') }}"
                 alt="403">
        </div>

        <div class="custom-error-actions">

            <a href="{{ url('/') }}"
               class="custom-home-btn">
                <i class="fas fa-home"></i>
                Home
            </a>

            <button type="button"
                    onclick="history.back()"
                    class="custom-back-btn">
                <i class="fas fa-arrow-left"></i>
                Go Back
            </button>

        </div>

    </div>

</body>
</html>
