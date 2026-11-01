<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Developer Error Monitor - Shwe Learn</title>

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #f5f7fa;
        color: #344054;
        font-family:
            -apple-system,
            BlinkMacSystemFont,
            "Segoe UI",
            Roboto,
            Arial,
            sans-serif;
    }

    .page {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px;
    }

    /* =========================
       HEADER
    ========================= */

    .header {
        margin-bottom: 24px;
    }

    .header h1 {
        margin: 0 0 6px;
        color: #1e3a5f;
        font-size: 26px;
        font-weight: 650;
    }

    .header p {
        margin: 0;
        color: #667085;
        font-size: 14px;
    }

    /* =========================
       STATISTICS
    ========================= */

    .stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e4e7ec;
        border-radius: 10px;
        padding: 20px;
    }

    .stat-label {
        color: #667085;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .stat-value {
        color: #1e3a5f;
        font-size: 28px;
        font-weight: 650;
    }

    /* =========================
       TOOLBAR
    ========================= */

    .toolbar {
        background: #ffffff;
        border: 1px solid #e4e7ec;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 18px;
    }

    .filters {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .filter {
        display: inline-flex;
        align-items: center;
        padding: 8px 14px;
        border: 1px solid #d0d5dd;
        border-radius: 6px;
        background: #ffffff;
        color: #475467;
        text-decoration: none;
        font-size: 13px;
    }

    .filter:hover {
        background: #f8fafc;
    }

    .filter.active {
        background: #1f5fae;
        border-color: #1f5fae;
        color: #ffffff;
    }

    .search-row {
        display: flex;
        gap: 10px;
        margin-top: 14px;
    }

    .search {
        flex: 1;
        min-width: 200px;
        padding: 10px 12px;
        border: 1px solid #d0d5dd;
        border-radius: 6px;
        font-size: 14px;
        outline: none;
    }

    .search:focus {
        border-color: #1f5fae;
        box-shadow: 0 0 0 2px rgba(31, 95, 174, 0.08);
    }

    .button {
        padding: 10px 16px;
        border: 1px solid #1f5fae;
        border-radius: 6px;
        background: #1f5fae;
        color: #ffffff;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
    }

    .button:hover {
        background: #174d91;
    }

    .button-secondary {
        background: #ffffff;
        border-color: #d0d5dd;
        color: #344054;
    }

    .button-secondary:hover {
        background: #f9fafb;
    }

    /* =========================
       LOG LIST
    ========================= */

    .logs {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .log-card {
        background: #ffffff;
        border: 1px solid #e4e7ec;
        border-radius: 10px;
        overflow: hidden;
    }

    .log-main {
        padding: 18px;
    }

    .log-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 10px;
    }

    .log-left {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .level {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 5px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .3px;
    }

    .level-error,
    .level-critical,
    .level-alert,
    .level-emergency {
        background: #fef3f2;
        color: #b42318;
    }

    .level-warning {
        background: #fffaeb;
        color: #b54708;
    }

    .level-notice {
        background: #eff8ff;
        color: #175cd3;
    }

    .level-info {
        background: #eff8ff;
        color: #175cd3;
    }

    .level-debug {
        background: #f2f4f7;
        color: #475467;
    }

    .time {
        color: #98a2b3;
        font-size: 12px;
    }

    .message {
        margin-bottom: 10px;
        color: #1d2939;
        font-size: 15px;
        font-weight: 550;
        line-height: 1.5;
        word-break: break-word;
    }

    .meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 20px;
        color: #667085;
        font-size: 12px;
    }

    .meta strong {
        color: #475467;
    }

    .visitor-account {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 5px;
        background: #eff8ff;
        color: #175cd3;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .3px;
    }

    .visitor-guest {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 5px;
        background: #f2f4f7;
        color: #475467;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .3px;
    }

    .url {
        word-break: break-all;
    }

    /* =========================
       DETAILS
    ========================= */

    .view-button {
        margin-top: 14px;
        padding: 7px 11px;
        border: 1px solid #d0d5dd;
        border-radius: 5px;
        background: #ffffff;
        color: #344054;
        font-size: 12px;
        cursor: pointer;
    }

    .view-button:hover {
        background: #f2f4f7;
    }

    .details {
        display: none;
        padding: 16px 18px;
        border-top: 1px solid #eaecf0;
        background: #fafbfc;
    }

    .details.open {
        display: block;
    }

    .details-title {
        margin-bottom: 8px;
        color: #475467;
        font-size: 12px;
        font-weight: 650;
    }

    .trace {
        margin: 0;
        padding: 14px;
        background: #111827;
        border-radius: 7px;
        color: #d1d5db;
        overflow-x: auto;
        font-family: "Cascadia Code", Consolas, monospace;
        font-size: 12px;
        line-height: 1.6;
        white-space: pre-wrap;
        word-break: break-word;
    }

    /* =========================
       EMPTY
    ========================= */

    .empty {
        background: #ffffff;
        border: 1px solid #e4e7ec;
        border-radius: 10px;
        padding: 60px 20px;
        text-align: center;
        color: #667085;
    }

    .empty-title {
        margin-bottom: 5px;
        color: #344054;
        font-weight: 600;
    }

    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 900px) {
        .stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 650px) {
        .page {
            padding: 16px;
        }

        .stats {
            grid-template-columns: 1fr;
        }

        .search-row {
            flex-direction: column;
        }

        .search,
        .button {
            width: 100%;
        }

        .log-top {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

</head>

<body>

<div class="page">
{{-- =========================================================
     HEADER
========================================================== --}}

<div class="header">

    <h1>
        Developer Error Monitor
    </h1>

    <p>
        Monitor application errors and diagnose problems.
    </p>

</div>


{{-- =========================================================
     STATISTICS
========================================================== --}}

<div class="stats">

    <div class="stat-card">

        <div class="stat-label">
            Total Errors
        </div>

        <div class="stat-value">
            {{ number_format($statistics['total']) }}
        </div>

    </div>


    <div class="stat-card">

        <div class="stat-label">
            Errors
        </div>

        <div class="stat-value">
            {{ number_format($statistics['errors']) }}
        </div>

    </div>


    <div class="stat-card">

        <div class="stat-label">
            Accounts Affected
        </div>

        <div class="stat-value">
            {{ number_format($statistics['accounts']) }}
        </div>

    </div>


    <div class="stat-card">

        <div class="stat-label">
            Today
        </div>

        <div class="stat-value">
            {{ number_format($statistics['today']) }}
        </div>

    </div>

</div>


{{-- =========================================================
     FILTER / SEARCH
========================================================== --}}

<div class="toolbar">

    <div class="filters">

        <a
            href="{{ route('developer.logs.index', [
                'visitor' => 'all',
                'search' => $search,
            ]) }}"
            class="filter {{ $visitor === 'all' ? 'active' : '' }}"
        >
            All
        </a>


        <a
            href="{{ route('developer.logs.index', [
                'visitor' => 'account',
                'search' => $search,
            ]) }}"
            class="filter {{ $visitor === 'account' ? 'active' : '' }}"
        >
            Accounts
        </a>


        <a
            href="{{ route('developer.logs.index', [
                'visitor' => 'guest',
                'search' => $search,
            ]) }}"
            class="filter {{ $visitor === 'guest' ? 'active' : '' }}"
        >
            Guests
        </a>

    </div>


    <form
        method="GET"
        action="{{ route('developer.logs.index') }}"
        class="search-row"
    >

        <input
            type="text"
            name="search"
            value="{{ $search }}"
            class="search"
            placeholder="Search errors, accounts or URLs..."
        >


        <input
            type="hidden"
            name="visitor"
            value="{{ $visitor }}"
        >


        <button
            type="submit"
            class="button"
        >
            Search
        </button>


        <a
            href="{{ route('developer.logs.index') }}"
            class="button button-secondary"
        >
            Refresh
        </a>

    </form>

</div>


{{-- =========================================================
     LOG ENTRIES
========================================================== --}}

@if ($entries->count())

    <div class="logs">

        @foreach ($entries as $index => $entry)

            <div class="log-card">

                <div class="log-main">

                    {{-- =================================================
                         TOP
                    ================================================== --}}

                    <div class="log-top">

                        <div class="log-left">

                            <span
                                class="level level-{{ strtolower($entry['level']) }}"
                            >
                                {{ $entry['level'] }}
                            </span>


                            <span class="time">
                                {{ $entry['timestamp'] }}
                            </span>

                        </div>


                        {{-- Visitor type --}}

                        @if ($entry['visitor_type'] === 'account')

                            <span class="visitor-account">
                                ACCOUNT
                            </span>

                        @else

                            <span class="visitor-guest">
                                GUEST
                            </span>

                        @endif

                    </div>


                    {{-- =================================================
                         ERROR MESSAGE
                    ================================================== --}}

                    <div class="message">
                        {{ $entry['message'] }}
                    </div>


                    {{-- =================================================
                        VISITOR INFORMATION
                    ================================================== --}}

                    <div class="meta">

                        @if (($entry['visitor_type'] ?? 'guest') === 'account')

                            <span>
                                <strong>Account:</strong>

                                {{ $entry['visitor_name'] ?? 'Unknown account' }}
                            </span>


                            @if (!empty($entry['visitor_email']))

                                <span>
                                    <strong>Email:</strong>

                                    {{ $entry['visitor_email'] }}
                                </span>

                            @endif


                            @if (!empty($entry['user_id']))

                                <span>
                                    <strong>User ID:</strong>

                                    {{ $entry['user_id'] }}
                                </span>

                            @endif


                            @if (!empty($entry['visitor_guard']))

                                <span>
                                    <strong>Guard:</strong>

                                    {{ $entry['visitor_guard'] }}
                                </span>

                            @endif

                        @else

                            <span>
                                <strong>Visitor:</strong>
                                Non-account visitor
                            </span>

                        @endif


                        @if (!empty($entry['method']))

                            <span>
                                <strong>Method:</strong>

                                {{ $entry['method'] }}
                            </span>

                        @endif

                    </div>


                    {{-- =================================================
                         URL
                    ================================================== --}}

                    @if ($entry['url'])

                        <div
                            class="meta"
                            style="margin-top: 8px;"
                        >

                            <span class="url">

                                <strong>URL:</strong>

                                {{ $entry['url'] }}

                            </span>

                        </div>

                    @endif


                    {{-- =================================================
                         TECHNICAL INFORMATION
                    ================================================== --}}

                    <div
                        class="meta"
                        style="margin-top: 8px;"
                    >

                        @if ($entry['exception'])

                            <span>

                                <strong>
                                    Exception:
                                </strong>

                                {{ $entry['exception'] }}

                            </span>

                        @endif


                        @if ($entry['file'])

                            <span>

                                <strong>
                                    File:
                                </strong>

                                {{ $entry['file'] }}

                                @if ($entry['line'])
                                    :{{ $entry['line'] }}
                                @endif

                            </span>

                        @endif

                    </div>


                    {{-- =================================================
                         VIEW STACK TRACE
                    ================================================== --}}

                    @if ($entry['trace'])

                        <button
                            type="button"
                            class="view-button"
                            onclick="toggleDetails({{ $index }})"
                        >
                            View Details
                        </button>

                    @endif

                </div>


                {{-- =====================================================
                     STACK TRACE
                ====================================================== --}}

                @if ($entry['trace'])

                    <div
                        class="details"
                        id="details-{{ $index }}"
                    >

                        <div class="details-title">
                            Stack Trace
                        </div>

                        <pre class="trace">{{ $entry['trace'] }}</pre>

                    </div>

                @endif

            </div>

        @endforeach

    </div>

@else

    <div class="empty">

        <div class="empty-title">
            No log entries found
        </div>

        <div>
            Try changing your filters or search term.
        </div>

    </div>

@endif
</div>

<script>

    function toggleDetails(index)
    {
        const details =
            document.getElementById(
                'details-' + index
            );

        if (!details) {
            return;
        }

        details.classList.toggle('open');
    }

</script>

</body>

</html>
