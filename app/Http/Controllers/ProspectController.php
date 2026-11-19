<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Media;
use App\Models\Municipality;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Prospect;
use App\Models\Screen;
use App\Services\PitchAdvisor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProspectController extends Controller
{
    public function index(Request $request)
    {
        $query = Prospect::with('municipality');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($priority = $request->get('priority')) {
            $query->where('priority', $priority);
        }

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        if ($municipality = $request->get('municipality')) {
            $query->where('municipality_id', $municipality);
        }

        $statusCounts = Prospect::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $prospects = $query->latest()->paginate(20)->withQueryString();
        $categories = Prospect::whereNotNull('category')->distinct()->pluck('category')->sort();
        $municipalities = Municipality::whereHas('prospects')->orderBy('name')->get();

        return view('prospects.index', compact('prospects', 'statusCounts', 'categories', 'municipalities'));
    }

    public function create()
    {
        $municipalities = Municipality::orderBy('name')->get();

        return view('prospects.create', compact('municipalities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'municipality_id' => 'nullable|exists:municipalities,id',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:100',
            'data_source' => 'nullable|string|max:100',
            'nearby_screen' => 'nullable|string|max:100',
            'priority' => 'nullable|in:A,B,C',
            'status' => 'required|in:'.implode(',', array_keys(Prospect::STATUSES)),
            'plan_of_interest' => 'nullable|string|max:100',
            'visit_date' => 'nullable|date',
            'next_followup_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('prospect-logos', 'public');
        }

        unset($validated['logo']);
        Prospect::create($validated);

        return redirect()->route('prospects.index')->with('success', 'Prospecto creado exitosamente.');
    }

    public function show(Prospect $prospect)
    {
        $prospect->load(['municipality', 'interactions.user']);
        $pitchAdvice = PitchAdvisor::getAdvice($prospect->category ?? '');
        $whatsappTemplates = $prospect->phone
            ? PitchAdvisor::whatsappTemplates($prospect->business_name, $prospect->category ?? '', $prospect->contact_name)
            : [];

        return view('prospects.show', compact('prospect', 'pitchAdvice', 'whatsappTemplates'));
    }

    public function edit(Prospect $prospect)
    {
        $municipalities = Municipality::orderBy('name')->get();

        return view('prospects.edit', compact('prospect', 'municipalities'));
    }

    public function update(Request $request, Prospect $prospect)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'municipality_id' => 'nullable|exists:municipalities,id',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:100',
            'data_source' => 'nullable|string|max:100',
            'nearby_screen' => 'nullable|string|max:100',
            'priority' => 'nullable|in:A,B,C',
            'status' => 'required|in:'.implode(',', array_keys(Prospect::STATUSES)),
            'plan_of_interest' => 'nullable|string|max:100',
            'visit_date' => 'nullable|date',
            'next_followup_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($prospect->logo_path) {
                Storage::disk('public')->delete($prospect->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('prospect-logos', 'public');
        }

        unset($validated['logo']);
        $prospect->update($validated);

        return redirect()->route('prospects.show', $prospect)->with('success', 'Prospecto actualizado exitosamente.');
    }

    public function uploadVideo(Request $request, Prospect $prospect)
    {
        $request->validate([
            'ad_video' => 'required|file|mimes:mp4,avi,mov,wmv,webm|max:102400',
        ]);

        if ($prospect->ad_video_path) {
            Storage::disk('public')->delete($prospect->ad_video_path);
        }

        $path = $request->file('ad_video')->store('prospect-videos', 'public');
        $prospect->update([
            'ad_video_path' => $path,
            'ad_video_original_name' => $request->file('ad_video')->getClientOriginalName(),
        ]);

        return redirect()->route('prospects.show', $prospect)->with('success', 'Video del anuncio subido exitosamente.');
    }

    public function deleteVideo(Prospect $prospect)
    {
        if ($prospect->ad_video_path) {
            Storage::disk('public')->delete($prospect->ad_video_path);
            $prospect->update(['ad_video_path' => null, 'ad_video_original_name' => null]);
        }

        return redirect()->route('prospects.show', $prospect)->with('success', 'Video eliminado.');
    }

    public function convertForm(Prospect $prospect)
    {
        $prospect->load('municipality');
        $plans = Plan::where('active', true)->orderBy('name')->get();
        $screens = Screen::orderBy('name')->get();

        return view('prospects.convert', compact('prospect', 'plans', 'screens'));
    }

    public function convert(Request $request, Prospect $prospect)
    {
        $validated = $request->validate([
            'deal_amount' => 'required|numeric|min:0',
            'payment_frequency' => 'required|in:'.implode(',', array_keys(Prospect::PAYMENT_FREQUENCIES)),
            'payment_start_date' => 'required|date',
            'payment_end_date' => 'required|date|after_or_equal:payment_start_date',
            'plan_id' => 'nullable|exists:plans,id',
            'screen_id' => 'nullable|exists:screens,id',
            'space_number' => 'nullable|integer|min:1',
            'email' => 'nullable|email|max:255',
            'rtn' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'ad_video' => 'nullable|file|mimes:mp4,avi,mov,wmv,webm|max:102400',
        ]);

        return DB::transaction(function () use ($request, $prospect, $validated) {
            $client = Client::create([
                'business_name' => $prospect->business_name,
                'contact_name' => $prospect->contact_name ?? $prospect->business_name,
                'email' => $validated['email'] ?? null,
                'phone' => $prospect->phone,
                'rtn' => $validated['rtn'] ?? null,
                'address' => $prospect->address,
                'municipality_id' => $prospect->municipality_id,
                'status' => 'activo',
                'notes' => $validated['notes'] ?? $prospect->notes,
            ]);

            $contract = null;
            if ($validated['plan_id'] ?? null) {
                $contract = Contract::create([
                    'contract_number' => Contract::generateContractNumber(),
                    'client_id' => $client->id,
                    'plan_id' => $validated['plan_id'],
                    'start_date' => $validated['payment_start_date'],
                    'end_date' => $validated['payment_end_date'],
                    'monthly_amount' => $validated['deal_amount'],
                    'total_amount' => $this->calculateTotalAmount(
                        $validated['deal_amount'],
                        $validated['payment_start_date'],
                        $validated['payment_end_date']
                    ),
                    'payment_frequency' => $this->mapPaymentFrequency($validated['payment_frequency']),
                    'status' => 'activo',
                ]);

                if ($validated['screen_id'] ?? null) {
                    $contract->screens()->attach($validated['screen_id'], [
                        'space_number' => $validated['space_number'] ?? 1,
                    ]);
                }

                $this->generatePayments($contract, $validated['payment_frequency']);
            }

            $videoPath = $prospect->ad_video_path;
            if ($request->hasFile('ad_video')) {
                $videoPath = $request->file('ad_video')->store('client-media', 'public');
            }

            if ($videoPath) {
                Media::create([
                    'client_id' => $client->id,
                    'contract_id' => $contract?->id,
                    'name' => $prospect->ad_video_original_name ?? ($prospect->business_name.' - Anuncio'),
                    'type' => 'video',
                    'file_path' => $videoPath,
                    'mime_type' => 'video/mp4',
                    'status' => 'activo',
                ]);
            }

            $prospect->update([
                'status' => 'firmado',
                'deal_amount' => $validated['deal_amount'],
                'payment_frequency' => $validated['payment_frequency'],
                'payment_start_date' => $validated['payment_start_date'],
                'payment_end_date' => $validated['payment_end_date'],
                'converted_to_client_id' => $client->id,
                'converted_at' => now(),
            ]);

            return redirect()->route('clients.show', $client)
                ->with('success', "Prospecto «{$prospect->business_name}» convertido a cliente exitosamente.");
        });
    }

    private function calculateTotalAmount(float $monthlyAmount, string $startDate, string $endDate): float
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $months = max(1, $start->diffInMonths($end));

        return round($monthlyAmount * $months, 2);
    }

    private function mapPaymentFrequency(string $frequency): string
    {
        return match ($frequency) {
            'quincenal', 'semanal' => 'mensual',
            'unico' => 'anual',
            default => $frequency,
        };
    }

    private function generatePayments(Contract $contract, string $frequency): void
    {
        $start = $contract->start_date->copy();
        $end = $contract->end_date->copy();
        $amount = $contract->monthly_amount;

        $dates = [];
        $current = $start->copy();

        while ($current->lte($end)) {
            $dates[] = $current->copy();
            $current = match ($frequency) {
                'semanal' => $current->addWeek(),
                'quincenal' => $current->addDays(15),
                'mensual' => $current->addMonth(),
                'unico' => $end->addDay(),
            };
        }

        if ($frequency === 'unico') {
            $dates = [$start->copy()];
        }

        $paymentAmount = count($dates) > 0
            ? round($contract->total_amount / count($dates), 2)
            : $contract->total_amount;

        foreach ($dates as $date) {
            Payment::create([
                'contract_id' => $contract->id,
                'client_id' => $contract->client_id,
                'amount' => $paymentAmount,
                'due_date' => $date,
                'status' => 'pendiente',
            ]);
        }
    }

    public function destroy(Prospect $prospect)
    {
        if ($prospect->logo_path) {
            Storage::disk('public')->delete($prospect->logo_path);
        }

        $prospect->delete();

        return redirect()->route('prospects.index')->with('success', 'Prospecto eliminado exitosamente.');
    }
}
