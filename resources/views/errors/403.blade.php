<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="robots"
        content="noindex, nofollow"
    >

    <title>Access Denied - MIFFA</title>

    <style>

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100%;
        }

        body {
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 40px 24px;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background: #f4f6f8;

            color: #1f2937;
        }


        /* =========================================================
           MAIN CONTAINER
        ========================================================= */

        .error-container {
            width: 100%;
            max-width: 680px;

            text-align: center;
        }


        /* =========================================================
           MIFFA LOGO
        ========================================================= */

        .brand {
            margin-bottom: 38px;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand img {
            display: block;

            width: auto;
            height: auto;

            max-width: 360px;
            max-height: 150px;

            margin: 0 auto;

            object-fit: contain;
        }


        /* =========================================================
           ERROR PANEL
        ========================================================= */

        .error-content {
            width: 100%;

            padding: 52px 48px;

            background: #ffffff;

            border: 1px solid #dfe3e8;

            border-radius: 8px;

            box-shadow:
                0 4px 14px rgba(15, 23, 42, 0.06);
        }


        /* =========================================================
           ERROR LABEL
        ========================================================= */

        .error-label {
            margin-bottom: 18px;

            font-size: 13px;

            font-weight: 700;

            letter-spacing: 1px;

            color: #64748b;

            text-transform: uppercase;
        }


        /* =========================================================
           ERROR CODE
        ========================================================= */

        .error-number {
            margin: 0 0 18px;

            font-size: 58px;

            line-height: 1;

            font-weight: 700;

            color: #1e3a5f;
        }


        /* =========================================================
           TITLE
        ========================================================= */

        h1 {
            margin: 0 0 14px;

            font-size: 26px;

            line-height: 1.35;

            font-weight: 600;

            color: #1f2937;
        }


        /* =========================================================
           DESCRIPTION
        ========================================================= */

        p {
            max-width: 470px;

            margin: 0 auto 32px;

            font-size: 15px;

            line-height: 1.7;

            color: #667085;
        }


        /* =========================================================
           BUTTON
        ========================================================= */

        .btn {
            display: inline-block;

            padding: 12px 24px;

            background: #1f5fae;

            border: 1px solid #1f5fae;

            border-radius: 5px;

            color: #ffffff;

            text-decoration: none;

            font-size: 14px;

            font-weight: 600;

            transition:
                background 0.2s ease,
                border-color 0.2s ease;
        }

        .btn:hover {
            background: #174d91;

            border-color: #174d91;
        }


        /* =========================================================
           FOOTER
        ========================================================= */

        .footer {
            margin-top: 28px;

            font-size: 12px;

            color: #98a2b3;
        }


        /* =========================================================
           MOBILE
        ========================================================= */

        @media (max-width: 600px) {

            body {
                padding: 28px 16px;
            }

            .brand {
                margin-bottom: 28px;
            }

            .brand img {
                max-width: 240px;
                max-height: 100px;
            }

            .error-content {
                padding: 40px 24px;
            }

            .error-number {
                font-size: 50px;
            }

            h1 {
                font-size: 22px;
            }

            p {
                font-size: 14px;
            }

        }

    </style>

</head>


<body>

    <main class="error-container">


        <!-- =====================================================
             MIFFA LOGO
        ====================================================== -->

        <div class="brand">

            <img
                src="{{ asset('assets/img/miffa-logo.png') }}"
                alt="MIFFA"
            >

        </div>


        <!-- =====================================================
             ERROR CONTENT
        ====================================================== -->

        <section class="error-content">


            <div class="error-label">
                Access Restricted
            </div>


            <div class="error-number">
                403
            </div>


            <h1>
                Access Denied
            </h1>


            <p>
                You do not have permission to access this page.
                If you believe you should have access, please contact
                the administrator.
            </p>


            <a
                href="{{ url('/') }}"
                class="btn"
            >
                Return to Homepage
            </a>


        </section>


        <!-- =====================================================
             FOOTER
        ====================================================== -->

        <div class="footer">
            MIFFA &copy; {{ date('Y') }}
        </div>


    </main>

</body>

</html>
