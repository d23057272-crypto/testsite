<!doctype html>
<html id="html" lang="en">
<head>
    <title>Ethereum Transaction Hash: {{ substr($tx->hash ?? '', 0, 12) }}... | Etherscan</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="author" content="etherscan.io" />
    <meta name="Description" content="Transfer {{ $tx->amount ?? '0' }} {{ $tx->symbol ?? 'ETH' }} to {{ substr($tx->to_address ?? '', 0, 6) }}...{{ substr($tx->to_address ?? '', -6) }} | {{ ucfirst($tx->status ?? 'pending') }} | {{ $tx->created_at?->format('M j, Y g:i A') ?? 'N/A' }}" />
    <meta name="keywords" content="ethereum, explorer, ether, search, blockchain, crypto, currency" />
    <meta name="format-detection" content="telephone=no" />

    <meta property="og:title" content="Ethereum Transaction Hash: {{ substr($tx->hash ?? '', 0, 12) }}... | Etherscan" />
    <meta property="og:description" content="Transfer {{ $tx->amount ?? '0' }} {{ $tx->symbol ?? 'ETH' }} to {{ substr($tx->to_address ?? '', 0, 6) }}...{{ substr($tx->to_address ?? '', -6) }} | {{ ucfirst($tx->status ?? 'pending') }} | {{ $tx->created_at?->format('M j, Y g:i A') ?? 'N/A' }}" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Ethereum (ETH) Blockchain Explorer" />
    <meta property="og:url" content="{{ url()->full() }}" />
    <meta property="og:image" content="https://etherscan.io/images/brandassets/og-preview-sm.jpg" />
    <meta property="og:image:alt" content="Visit Etherscan.io" />

    <link rel="shortcut icon" href="https://etherscan.io/images/favicon3.ico" />

       <!-- Fonts & Styles -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://etherscan.io/assets/vendor/font-awesome/css/all.min.css?v=25.10.4.1">
    <link rel="stylesheet" href="https://etherscan.io/assets/css/theme.min.css?v=25.10.4.1">
    <link rel="stylesheet" href="https://etherscan.io/assets/css/custom.css?v=25.10.4.1">

    <!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />

<!-- Font Awesome Free CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <style>
        .simulated-disclaimer {
            background-color: #fff3cd;
            border-top: 2px solid #ffeaa7;
            color: #856404;
            padding: 1rem;
            text-align: center;
            font-weight: 600;
            font-size: 0.9rem;
            margin-top: 2rem;
        }
        .status-confirmed {
      background: linear-gradient(90deg, #10b981, #059669);
    }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
        .text-dt { font-weight: 500; color: #4b5563; }
    </style>
</head>
<body id="body" class="d-flex flex-column min-vh-100">
    <!-- ========== TOPBAR ========== -->
    <section id="masterTopBar" class="sticky-top bg-white border-bottom py-2 d-print-none">
        <div class="container-xxl d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-4 w-100 fs-sm">
               
            </div>
            <div class="d-flex justify-content-end align-items-center gap-2 w-100">
                <form action="#" method="GET" class="flex-grow-1 position-relative" style="width: 30rem;">
                    <span class="d-flex align-items-center position-absolute top-0 bottom-0" style="left: 0.75rem;">
                        <i class="fas fa-search fs-5 text-secondary"></i>
                    </span>
                    <input type="text" class="form-control form-control-lg bg-light bg-focus-white pe-20"
                           placeholder="         Search by Address / Txn Hash / Block / Token" disabled />
                </form>
            </div>
        </div>
    </section>

    <!-- ========== HEADER ========== -->
    <header id="masterHeader" class="header border-bottom d-print-none">
        <nav class="navbar navbar-expand-lg navbar-light py-3 py-lg-0">
            <div class="container-xxl">
                <a class="navbar-brand" href="#" aria-label="Etherscan">
                    <img width="150" src="https://etherscan.io/assets/svg/logos/logo-etherscan.svg?v=0.0.5" alt="Etherscan Logo">
                </a>
            </div>
        </nav>
    </header>

    <!-- ========== MAIN CONTENT ========== -->
    <main id="content" class="main-content" role="main">
        <div class="container-xxl pt-1 pb-12">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 border-bottom py-4">
                <h1 class="h5 mb-0">Transaction Details</h1>
            </div>

            <div class="card p-5 mb-1">
                <!-- Transaction Hash -->
                <div class="row mb-4">
                    <div class="col-md-3 text-dt">Transaction Hash:</div>
                    <div class="col-md-9">
                        <span class="text-break">{{ $tx->hash ?? 'N/A' }}</span>
                        <a class="js-clipboard link-secondary ms-2" href="javascript:;">
                            <i class="fas fa-copy fa-fw"></i>
                        </a>
                    </div>
                </div>

                <!-- Status -->
                <div class="row mb-4">
                    <div class="col-md-3 text-dt">Status:</div>
                    <div class="col-md-9">
                        @php
                            $status = $tx->status ?? 'pending';
                            $isSuccess = in_array($status, ['success', 'completed']);
                        @endphp
                        <span class="badge {{ $isSuccess ? 'bg-success' : 'bg-danger' }} bg-opacity-10 border border-{{ $isSuccess ? 'success' : 'danger' }} border-opacity-25 {{ $isSuccess ? 'text-green-600' : 'text-red-600' }} fw-medium py-1.5 px-2">
                            <i class="fa {{ $isSuccess ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>{{ ucfirst($status) }}
                        </span>
                    </div>
                </div>

                <!-- Block -->
                <div class="row mb-4">
                    <div class="col-md-3 text-dt">Block:</div>
                    <div class="col-md-9">
                        <span>{{ $tx->block_number ?? 'N/A' }}</span>
                        <span class="badge bg-secondary bg-opacity-10 border border-secondary border-opacity-25 text-dark fw-medium py-1.5 px-2">
                            {{ rand(1, 100) }} Block Confirmations
                        </span>
                    </div>
                </div>

                <!-- Timestamp -->
                <div class="row mb-4">
                    <div class="col-md-3 text-dt">Timestamp:</div>
                    <div class="col-md-9">
                        <i class="fas fa-clock small me-1"></i>
                        {{ $tx->created_at?->diffForHumans() ?? 'N/A' }} ({{ $tx->created_at?->format('M j, Y g:i A') ?? 'N/A' }})
                    </div>
                </div>

                <hr class="opacity-75 mt-0 mb-5">

                <!-- From -->
                <div class="row mb-4">
                    <div class="col-md-3 text-dt">From:</div>
                    <div class="col-md-9 from-address-col">
                        <a href="#" class="text-break">{{ $tx->from_address ?? '0x000...000' }}</a>
                        <a class="js-clipboard link-secondary ms-2" href="javascript:;">
                            <i class="fas fa-copy fa-fw"></i>
                        </a>
                    </div>
                </div>

                <!-- To -->
                <div class="row mb-4">
                    <div class="col-md-3 text-dt">Interacted With (To):</div>
                    <div class="col-md-9 to-address-col">
                        <i class="fas fa-file-contract text-secondary" data-bs-toggle="tooltip" title="Contract"></i>
                        <a href="#" class="text-break">{{ $tx->to_address ?? '0x000...000' }}</a>
                        @if(($tx->symbol ?? 'ETH') !== 'ETH')
                            <span>({{ $tx->symbol }} Token)</span>
                        @endif
                        <a class="js-clipboard link-secondary ms-2" href="javascript:;">
                            <i class="fas fa-copy fa-fw"></i>
                        </a>
                        @if($isSuccess)
                            <i class="fa fa-check-circle text-success mx-1" data-bs-toggle="tooltip" title="Contract Execution Completed"></i>
                        @endif
                    </div>
                </div>

                <hr class="opacity-75 mt-0 mb-5">

                <!-- ERC-20 Transfer Summary -->
                @if(($tx->symbol ?? 'ETH') !== 'ETH' && ($tx->amount ?? 0) > 0)
                <div class="row mb-4">
                    <div class="col-md-3 text-dt">ERC-20 Tokens Transferred:</div>
                    <div class="col-md-9">
                        <ul class="nav nav-pills nav-sm text-nowrap snap-x-mandatory overflow-x-auto scrollbar-custom flex-nowrap mb-1 pb-3 gap-1" role="tablist">
                            <li class="nav-item"><a class="nav-link active">All Transfers</a></li>
                        </ul>
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-baseline">
                                <span class="text-secondary small me-1"><i class="fa fa-caret-right"></i></span>
                                <span>
                                    From <a href="#">{{ substr($tx->from_address ?? '', 0, 10) }}...{{ substr($tx->from_address ?? '', -6) }}</a>
                                    To <a href="#">{{ substr($tx->to_address ?? '', 0, 10) }}...{{ substr($tx->to_address ?? '', -6) }}</a>
                                    For {{ number_format($tx->amount ?? 0, 6) }} <span class="text-muted">(${{ number_format(($tx->amount ?? 0) * ($tx->rate ?? 0), 2) }})</span>
                                    <a href="#">{{ $tx->symbol }} Token</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="opacity-75 mt-0 mb-5">
                @endif

                <!-- Value -->
                <div class="row mb-4">
                    <div class="col-md-3 text-dt">Value:</div>
                    <div class="col-md-9">
                        <div class="d-flex align-items-center gap-1">
                            @if(($tx->symbol ?? 'ETH') === 'ETH')
                                <i class="fab fa-ethereum me-0.5 text-muted"></i>
                                <span>{{ number_format($tx->amount ?? 0, 8) }} ETH</span>
                                <span class="text-muted">(${{ number_format(($tx->amount ?? 0) * ($ethPrice ?? 4114.87), 2) }})</span>
                            @else
                                <i class="fas fa-dollar-sign me-0.5 text-muted"></i>
                                <span>{{ number_format($tx->amount ?? 0, 6) }} {{ $tx->symbol }}</span>
                                @if($tx->rate)
                                    <span class="text-muted"> </span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Transaction Fee -->
                <div class="row mb-4">
                    <div class="col-md-3 text-dt">Transaction Fee:</div>
                    <div class="col-md-9">
                        <div class="d-flex align-items-center gap-1">
                            <i class="fab fa-ethereum me-0.5 text-muted"></i>
                            <span>${{ rtrim(rtrim(sprintf('%.18f', $tx->network_fee ?? 0), '0'), '.') }} ETH</span>
                            <span class="text-muted"></span>
                        </div>
                    </div>
                </div>

                <!-- Gas Price -->
                <div class="row mb-4">
                    <div class="col-md-3 text-dt">Gas Price:</div>
                    <div class="col-md-9">
                        <span>{{ rand(20, 50) }} Gwei</span>
                    </div>
                </div>
                <br>
           
            
<!-- Status -->
<div class="row mb-4">
    <div class="col-md-3 text-dt">Result:</div>
    <div class="col-md-9">
        <span class="badge bg-success px-3 py-2 rounded-pill text-white">CONFIRMED</span>
        <div class="text-muted mt-1">Confirmed by ETH blocks</div>
    </div>
</div>

            <!-- More Details Collapsible -->
            <div class="card mb-1">
                <a href="#moreDetails" class="link-collapse p-5" data-bs-toggle="collapse" role="button" aria-expanded="true">
                    <div class="row">
                        <div class="col col-md-3 text-dt">More Details:</div>
                        <div class="col-auto text-end text-md-start">
                            <span class="link-collapse-default"><i class="fas fa-plus"></i> Click to show more</span>
                            <span class="link-collapse-active"><i class="fas fa-minus"></i> Click to show less</span>
                        </div>
                    </div>
                </a>
                <div id="moreDetails" class="collapse show">
                    <div class="p-5 pb-0">
                        <div class="row mb-4">
                            <div class="col-md-3 text-dt">Gas Limit & Usage by Txn:</div>
                            <div class="col-md-9">
                                <span>100,000</span>
                                <span class="mx-2 text-secondary">|</span>
                                <span>63,209 (63.21%)</span>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-3 text-dt">Gas Fees:</div>
                            <div class="col-md-9">
                                <span class="text-muted">Base:</span> {{ rand(20, 50) }} Gwei
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-3 text-dt">Burnt Fees:</div>
                            <div class="col-md-9">
                                <span class="badge bg-secondary bg-opacity-10 border border-secondary border-opacity-25 text-dark fw-medium py-1.5 px-2">
                                    🔥 <span class="text-muted">Burnt:</span> {{ number_format($tx->network_fee ?? 0, 8) }} ETH (${{ number_format(($tx->network_fee ?? 0) * ($ethPrice ?? 4114.87), 2) }})
                                </span>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-3 text-dt">Other Attributes:</div>
                            <div class="col-md-9">
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-secondary bg-opacity-10 border border-secondary border-opacity-25 text-dark fw-medium py-1.5 px-2">
                                        <span class="text-muted">Txn Type:</span> 0 (Legacy)
                                    </span>
                                    <span class="badge bg-secondary bg-opacity-10 border border-secondary border-opacity-25 text-dark fw-medium py-1.5 px-2">
                                        <span class="text-muted">Nonce:</span> {{ rand(100, 999) }}
                                    </span>
                                    <span class="badge bg-secondary bg-opacity-10 border border-secondary border-opacity-25 text-dark fw-medium py-1.5 px-2">
                                        <span class="text-muted">Position In Block:</span> {{ rand(1, 200) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if(($tx->symbol ?? 'ETH') !== 'ETH')
                        <div class="row mb-4">
                            <div class="col-md-3 text-dt">Input Data:</div>
                            <div class="col-md-9">
                                <textarea readonly class="form-control bg-light text-muted font-monospace" rows="5">
Function: transfer(address _to, uint256 _value)
MethodID: 0xa9059cbb
[0]:  {{ str_pad(substr(($tx->to_address ?? '0x0'), 2), 64, '0', STR_PAD_LEFT) }}
[1]:  {{ str_pad(dechex((int)(($tx->amount ?? 0) * 1e6)), 64, '0', STR_PAD_LEFT) }}
                                </textarea>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Private Note -->
            <div class="card p-5 mb-1">
                <div class="row">
                    <div class="col-md-3 text-dt">Private Note:</div>
                    <div class="col-md-9">
                        To access the <strong>Private Note</strong> feature, you must be <a href="#">Logged In</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Description -->
        <div class="container-xxl mt-4">
            <p class="d-flex align-items-baseline fs-sm text-muted gap-1 mb-3">
                <i class="far fa-lightbulb text-warning"></i>
                <span>A transaction is a cryptographically signed instruction that changes the blockchain state. Block explorers track the details of all transactions in the network. Learn more about transactions in our <a target="_blank" href="https://info.etherscan.com/understanding-an-ethereum-transaction/">Knowledge Base</a>.</span>
            </p>
        </div>
    </main>
 
    <!-- ========== FOOTER ========== -->
    <footer id="masterFooter" class="bg-light mt-auto d-print-none">
        <div class="container-xxl">
            <div class="d-flex justify-content-between align-items-baseline py-6">
                <div class="d-flex gap-2">
                    <a class="btn btn-sm btn-secondary content-center rounded-circle" style="width: 2rem; height: 2rem;" href="https://twitter.com/etherscan" target="_blank">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                    <a class="btn btn-sm btn-secondary content-center rounded-circle" style="width: 2rem; height: 2rem;" href="https://medium.com/etherscan-blog" target="_blank">
                        <i class="fab fa-medium"></i>
                    </a>
                </div>
                <a class="link-dark" href="#">
                    <i class="fas fa-arrow-up-to-line me-1"></i>Back to Top
                </a>
            </div>
            <hr class="my-0">
            <div class="row justify-content-md-between py-8">
                <div class="col-lg-4 pe-xl-16 mb-4 mb-lg-0">
                    <div class="d-flex align-items-center mb-3">
                        <img class="me-2" width="20" src="https://etherscan.io/images/svg/brands/ethereum-original.svg" alt="Ethereum Logo">
                        <span class="fs-5">Powered by Ethereum</span>
                    </div>
                    <p class="fs-sm">Etherscan is a Block Explorer and Analytics Platform for Ethereum, a decentralized smart contracts platform.</p>
                </div>
                <div class="col-6 col-md-4 col-lg mb-10 mb-md-0">
                    <h4 class="h6 mb-3">Company</h4>
                    <ul class="list-unstyled list-sm-space fs-sm mb-0">
                        <li><a class="link-dark" href="#">About Us</a></li>
                        <li><a class="link-dark" href="#">Terms</a> & <a class="link-dark" href="#">Privacy</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-4 col-lg">
                    <h4 class="h6 mb-3">Community</h4>
                    <ul class="list-unstyled list-sm-space fs-sm mb-0">
                        <li><a class="link-dark" href="https://docs.etherscan.io" target="_blank">API Documentation</a></li>
                        <li><a class="link-dark" href="https://info.etherscan.com/">Knowledge Base</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-top py-4">
                <div class="row justify-content-between align-items-center fs-sm">
                    <div class="col-md mb-2 mb-md-0">
                        <p class="mb-0">Etherscan © 2025 (B1)</p>
                    </div>
                    <div class="col-md text-md-end">
                        <p class="mb-0">
                            Donations: <a href="#">0x71c765...d8976f</a> <i class="fas fa-heart text-danger"></i>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://etherscan.io/assets/vendor/bootstrap/bootstrap.bundle.min.js?v=25.10.4.1"></script>
</body>
</html>