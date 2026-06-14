<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Document;
use App\Models\Source;
use App\Models\Thematique;
use App\Models\User;
use App\Rules\MinWords;
use App\Support\DocumentStorage;
use Illuminate\Http\Request;

class DocumentSubmissionController extends Controller
{
    public function create()
    {
        $sources = Source::orderBy('label')->get();
        $thematiques = Thematique::orderBy('label')->get();
        $categories = Category::orderBy('label')->get();

        return view('document.submit-public', compact('sources', 'thematiques', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'website' => 'nullable|max:0',
            'submitter_name' => 'required|string|min:2|max:120',
            'submitter_email' => 'required|email|max:190',
            'title' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'resume' => ['required', 'string', new MinWords(250)],
            'file_doc' => 'required|mimes:pdf|max:30000',
            'picture' => 'nullable|image|max:5120',
            'source_id' => 'nullable|integer|exists:sources,id',
            'thematique_id' => 'nullable|array',
            'thematique_id.*' => 'integer|exists:thematiques,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'page' => 'nullable|integer|min:1',
        ]);

        $adminId = User::whereIn('role_id', [1, 2])->value('id');

        $documentFile = DocumentStorage::store($request->file('file_doc'));

        $pictureFile = '';
        if ($request->hasFile('picture')) {
            $pictureFile = date('YmdHis') . '_cover.' . $request->file('picture')->getClientOriginalExtension();
            $request->file('picture')->storeAs('picture', $pictureFile, 'public');
        }

        $thematiqueIds = $request->thematique_id ?? [];
        $sourceId = $request->source_id ?? Source::query()->value('id');

        $adminId = User::whereIn('role_id', [1, 2])->value('id');

        if (!$sourceId || !$adminId) {
            return back()->with('error', 'Impossible de soumettre pour le moment. Contactez l\'administrateur.')->withInput();
        }

        $category = $request->category_id
            ? Category::find($request->category_id)
            : Category::where('label', 'Soumission publique')->first();

        Document::create([
            'user_id' => $adminId,
            'source_id' => $sourceId,
            'thematique_id' => json_encode($thematiqueIds),
            'title' => $request->title,
            'auteur' => $request->auteur,
            'resume' => $request->resume,
            'category_id' => $category?->id,
            'categorie' => $category?->label ?? 'Soumission publique',
            'page' => $request->page ?? 1,
            'edition' => '',
            'publication_date' => now(),
            'file_doc' => $documentFile,
            'picture' => $pictureFile,
            'statut_publication' => 0,
            'ask_form' => 0,
            'is_guest_submission' => true,
            'submitter_name' => $request->submitter_name,
            'submitter_email' => $request->submitter_email,
        ]);

        return redirect()->route('home')->with('success', 'Votre document a été envoyé. Il sera visible après validation par un administrateur.');
    }
}
