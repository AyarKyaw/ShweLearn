<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Digital Pass - Shwe Learn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Libraries for PNG & PDF Exports -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .id-card-bg {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }

        .watermark-bg {
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 16px 16px;
            opacity: 0.25;
        }

        /* Prevents vertical baseline shifts during html2canvas rendering */
        #digitalCard * {
            box-sizing: border-box;
            transform: translateZ(0);
        }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen pb-16">

    <!-- Top Navigation -->
    <header class="border-b border-slate-800 bg-slate-950/50 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-white transition bg-slate-800/80 hover:bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-700/60">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Main Website</span>
                </a>
                <div class="h-4 w-px bg-slate-800"></div>
                <div class="flex items-center gap-2.5">
                    <!-- Top Nav Logo -->
                    <img src="{{ asset('assets/img/icon/miffas.png') }}" alt="Shwe Learn Logo" class="h-9 w-auto object-contain">
                    <span class="font-bold tracking-tight text-white text-sm">Alumni Portal</span>
                </div>
            </div>
            
            <form action="{{ route('alumni.logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-semibold text-slate-400 hover:text-rose-400 transition flex items-center gap-2 bg-slate-800/60 hover:bg-slate-800 px-3 py-2 rounded-lg border border-slate-700/50">
                    <i class="fa-solid fa-right-from-bracket"></i> Sign Out
                </button>
            </form>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 mt-10">
        
        <!-- Header & Action Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-white tracking-tight">Alumni Dashboard</h1>
                <p class="text-xs text-slate-400 mt-1">Manage your active membership credentials and digital pass</p>
            </div>
            
            <div class="flex items-center gap-3">
                <button onclick="downloadPNG()" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white px-4 py-2.5 rounded-xl text-xs font-semibold transition shadow-lg shadow-blue-600/20">
                    <i class="fa-solid fa-image"></i> Download PNG
                </button>
                <button onclick="downloadPDF()" class="flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 px-4 py-2.5 rounded-xl text-xs font-semibold border border-slate-700 transition">
                    <i class="fa-solid fa-file-pdf text-rose-400"></i> Download PDF
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Profile Overview Card -->
            <div class="lg:col-span-4 bg-slate-800/50 border border-slate-700/60 rounded-3xl p-6 text-center shadow-xl backdrop-blur-xl">
                <div class="relative w-32 h-32 mx-auto mb-5">
                    <img src="{{ $alumni->image ? asset('storage/' . $alumni->image) : 'https://via.placeholder.com/150' }}" 
                         alt="{{ $alumni->name }}" 
                         class="w-32 h-32 rounded-full object-cover border-4 border-slate-700 shadow-xl">
                    <div class="absolute bottom-1 right-1 w-5 h-5 {{ $alumni->status === 'active' ? 'bg-emerald-500' : 'bg-amber-500' }} rounded-full border-2 border-slate-800" title="{{ ucfirst($alumni->status ?? 'active') }} Account"></div>
                </div>

                <h2 class="text-xl font-bold text-white tracking-tight">{{ $alumni->name }}</h2>
                <p class="text-xs text-slate-400 font-medium mt-1 mb-6">
                    <i class="fa-solid fa-graduation-cap text-blue-400 mr-1.5"></i>{{ $alumni->course->title ?? $alumni->course->name ?? 'Shwe Learn Education' }}
                </p>

                <!-- Status Pill -->
                @if(($alumni->status ?? 'active') === 'active')
                    <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-semibold py-2.5 px-4 rounded-xl text-xs inline-flex items-center gap-2 mb-6 w-full justify-center">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Verified Alumni Member
                    </div>
                @else
                    <div class="bg-amber-500/10 border border-amber-500/20 text-amber-400 font-semibold py-2.5 px-4 rounded-xl text-xs inline-flex items-center gap-2 mb-6 w-full justify-center">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        Status: {{ ucfirst($alumni->status) }}
                    </div>
                @endif

                <div class="border-t border-slate-700/50 pt-5 text-left text-xs space-y-3">
                    <div class="flex justify-between text-slate-400">
                        <span>Email:</span>
                        <span class="text-slate-200 font-medium truncate max-w-[180px]">{{ $alumni->email }}</span>
                    </div>
                    <div class="flex justify-between text-slate-400">
                        <span>Member Since:</span>
                        <span class="text-slate-200 font-medium">{{ $alumni->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Right Digital ID Card -->
            <div class="lg:col-span-8">
                
                <div id="digitalCard" class="id-card-bg text-slate-800 rounded-3xl overflow-hidden shadow-2xl border border-slate-200/80 relative">
                    
                    <!-- Top Accent Bar -->
                    <div class="h-2.5 bg-gradient-to-r from-blue-700 via-indigo-600 to-blue-500"></div>

                    <div class="p-8 relative">
                        <div class="absolute inset-0 watermark-bg pointer-events-none"></div>

                        <!-- Card Header -->
                        <div class="flex items-center justify-between pb-6 border-b border-slate-200/80 relative z-10">
                            <div class="flex items-center gap-3.5">
                                <!-- Digital Pass Shwe Learn Logo -->
                                <img src="{{ asset('assets/img/icon/miffas.png') }}" alt="Shwe Learn Logo" class="h-12 w-auto object-contain">
                                <div>
                                    <h2 class="text-base font-extrabold text-slate-900 tracking-wider uppercase leading-normal">
                                        Shwe Learn Education Alumni
                                    </h2>
                                    <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mt-0.5 leading-normal">
                                        Official Digital Credential
                                    </p>
                                </div>
                            </div>
                            
                            <span class="{{ ($alumni->status ?? 'active') === 'active' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-amber-100 text-amber-700 border-amber-200' }} text-[10px] font-extrabold px-3 py-1 rounded-full border tracking-wider uppercase leading-normal">
                                {{ $alumni->status ?? 'Active' }}
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="grid grid-cols-12 gap-6 items-center py-6 relative z-10">
                            
                            <!-- Avatar Column -->
                            <div class="col-span-12 sm:col-span-4 flex justify-center">
                                <div class="w-36 h-36 rounded-2xl overflow-hidden border-2 border-slate-300 p-1.5 bg-white shadow-inner">
                                    <img src="{{ $alumni->image ? asset('storage/' . $alumni->image) : 'https://via.placeholder.com/150' }}" 
                                         alt="{{ $alumni->name }}" 
                                         class="w-full h-full rounded-xl object-cover">
                                </div>
                            </div>

                            <!-- Details Column -->
                            <div class="col-span-12 sm:col-span-8 grid grid-cols-2 gap-y-4 gap-x-3 text-left">
                                <div class="col-span-2">
                                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 leading-normal">Full Name</span>
                                    <span class="text-lg font-black text-slate-900 leading-normal block mt-0.5">{{ $alumni->name }}</span>
                                </div>

                                <div>
                                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 leading-normal">Register No</span>
                                    <span class="text-sm font-bold text-slate-800 font-mono tracking-tight leading-normal block mt-0.5">
                                        {{ $alumni->register_no ?? 'MEA-' . str_pad($alumni->id, 6, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>

                                <div>
                                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 leading-normal">Issue Date</span>
                                    <span class="text-xs font-bold text-slate-700 leading-normal block mt-0.5">{{ $alumni->created_at->format('d M Y') }}</span>
                                </div>

                                <div class="col-span-2">
                                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 leading-normal">Course / Batch</span>
                                    <span class="text-xs font-bold text-slate-800 leading-normal block mt-0.5 break-words">
                                        {{ $alumni->course->title ?? $alumni->course->name ?? 'ASEAN JAPAN' }}
                                    </span>
                                </div>

                                <div>
                                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 leading-normal">Valid Until</span>
                                    <span class="text-xs font-bold text-slate-700 leading-normal block mt-0.5">{{ $alumni->created_at->addYears(1)->format('d M Y') }}</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Card Footer Banner -->
                    <div class="bg-slate-900 px-8 py-4 flex items-center justify-between text-white relative z-10">
                        <div class="text-xs text-slate-400 font-medium space-y-1">
                            <p class="leading-normal">www.Shwe Learn.org.mm</p>
                            <p class="leading-normal">www.myanmarlogisticsinstitute.com</p>
                        </div>
                        
                        <!-- Bigger Dynamic Verification QR Code -->
                        <div class="bg-white p-1.5 rounded-xl shadow-md flex items-center justify-center">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(route('alumni.verify', $alumni->register_no ?? 'MEA-' . str_pad($alumni->id, 6, '0', STR_PAD_LEFT))) }}" 
                                 alt="QR Code Verification" 
                                 class="w-20 h-20">
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </main>

    <!-- Export Scripts -->
    <script>
        const canvasOptions = {
            scale: 3,
            useCORS: true,
            allowTaint: true,
            backgroundColor: null,
            scrollY: -window.scrollY
        };

        function downloadPNG() {
            const card = document.getElementById('digitalCard');
            
            html2canvas(card, canvasOptions).then(canvas => {
                const link = document.createElement('a');
                link.download = 'MIFFA-Card-{{ Str::slug($alumni->name) }}.png';
                link.href = canvas.toDataURL('image/png', 1.0);
                link.click();
            });
        }

        function downloadPDF() {
            const card = document.getElementById('digitalCard');
            const { jsPDF } = window.jspdf;

            html2canvas(card, canvasOptions).then(canvas => {
                const imgData = canvas.toDataURL('image/png', 1.0);
                const imgWidth = canvas.width;
                const imgHeight = canvas.height;

                const pdf = new jsPDF({
                    orientation: imgWidth > imgHeight ? 'landscape' : 'portrait',
                    unit: 'px',
                    format: [imgWidth, imgHeight]
                });

                pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
                pdf.save('MIFFA-Card-{{ Str::slug($alumni->name) }}.pdf');
            });
        }
    </script>
</body>
</html>