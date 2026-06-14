<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContentView;
use App\Models\Document;
use App\Models\DocumentReport;
use App\Models\Source;
use App\Models\Thematique;
use App\Rules\MinWords;
use App\Support\CoverImageStorage;
use App\Support\DocumentStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::idDescending();

        if ($request->get('status') === 'published') {
            $query->where('statut_publication', 1);
        } elseif ($request->get('status') === 'draft') {
            $query->where('statut_publication', 0)->where('is_guest_submission', false);
        } elseif ($request->get('status') === 'submissions') {
            $query->where('statut_publication', 0)->where('is_guest_submission', true);
        }

        $documents = $query->paginate(10)->withQueryString();

        return view('document.index', compact('documents'));
    }

    public function create()
    {
        $sources = Source::all();
        $thematiques = Thematique::all();
        $categories = Category::orderBy('label')->get();

        return view('document.create', compact('sources', 'thematiques', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_doc' => 'required|mimes:pdf|max:30000',
            'picture' => 'nullable|image|max:5120',
            'source_id' => 'required|integer|exists:sources,id',
            'thematique_id' => 'required|array|min:1',
            'thematique_id.*' => 'integer|exists:thematiques,id',
            'category_id' => 'required|integer|exists:categories,id',
            'page' => 'required|integer',
            'edition' => 'nullable|string',
            'publication_date' => 'required|date',
            'code_document' => 'nullable|string',
            'title' => 'required|string',
            'auteur' => 'required|string',
            'resume' => ['required', 'string', new MinWords(250)],
        ]);

        $statut_publication = $request->has('statut_publication') ? 1 : 0;
        $ask_form = $request->has('ask_form') ? 1 : 0;

        $documentFile = null;
        $pictureFile = null;

        if ($request->hasFile('file_doc')) {
            $documentFile = DocumentStorage::store($request->file('file_doc'));
        }

        if ($request->hasFile('picture')) {
            $pictureFile = CoverImageStorage::store($request->file('picture'));
        }

        $category = Category::findOrFail($request->category_id);

        $document = Document::create([
            'user_id' => Auth::id(),
            'source_id' => $request->source_id,
            'thematique_id' => json_encode($request->thematique_id),
            'resume' => $request->resume,
            'category_id' => $category->id,
            'categorie' => $category->label,
            'picture' => $pictureFile,
            'page' => $request->page,
            'edition' => $request->edition,
            'publication_date' => $request->publication_date,
            'file_doc' => $documentFile,
            'statut_publication' => $statut_publication,
            'ask_form' => $ask_form,
            'code_document' => $request->code_document,
            'title' => $request->title,
            'auteur' => $request->auteur,
        ]);

        return redirect()
            ->route('public.documents.show', $document)
            ->with('message', 'Document enregistré. Voici l\'aperçu tel qu\'il apparaîtra au public.');
    }

    public function show(Document $document)
    {
        $viewCount = ContentView::where('viewable_type', Document::class)
            ->where('viewable_id', $document->id)
            ->where('action', 'view')
            ->count();

        return view('document.show', compact('document', 'viewCount'));
    }

    public function edit(Document $document)
    {
        $sources = Source::all();
        $thematiques = Thematique::all();
        $categories = Category::orderBy('label')->get();

        return view('document.edit', compact('sources', 'thematiques', 'document', 'categories'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'file_doc' => 'nullable|mimes:pdf|max:40000',
            'picture' => 'nullable|image|max:5120',
            'source_id' => 'required|integer|exists:sources,id',
            'thematique_id' => 'required|array|min:1',
            'thematique_id.*' => 'integer|exists:thematiques,id',
            'resume' => ['required', 'string', new MinWords(250)],
            'category_id' => 'required|integer|exists:categories,id',
            'page' => 'nullable|integer',
            'edition' => 'nullable|string',
            'publication_date' => 'nullable|date',
            'title' => 'required|string',
            'auteur' => 'required|string',
        ]);

        $statut_publication = $request->has('statut_publication') ? 1 : 0;
        $ask_form = $request->has('ask_form') ? 1 : 0;

        $documentFile = $document->file_doc;
        if ($request->hasFile('file_doc')) {
            if ($document->file_doc) {
                DocumentStorage::delete($document->file_doc);
            }
            $documentFile = DocumentStorage::store($request->file('file_doc'));
        }

        $pictureFile = $document->picture;
        if ($request->hasFile('picture')) {
            CoverImageStorage::delete($document->picture);
            $pictureFile = CoverImageStorage::store($request->file('picture'));
        }

        $category = Category::findOrFail($request->category_id);

        $document->update([
            'user_id' => Auth::id(),
            'source_id' => $request->source_id,
            'thematique_id' => json_encode($request->thematique_id),
            'resume' => $request->resume,
            'category_id' => $category->id,
            'categorie' => $category->label,
            'picture' => $pictureFile,
            'page' => $request->page,
            'edition' => $request->edition,
            'publication_date' => $request->publication_date,
            'file_doc' => $documentFile,
            'statut_publication' => $statut_publication,
            'ask_form' => $ask_form,
            'code_document' => $request->code_document,
            'title' => $request->title,
            'auteur' => $request->auteur,
        ]);

        return redirect()->route('documents.index')->with('message', 'Document mis à jour');
    }

    public function destroy(Request $request, Document $document)
    {
        if ($document->file_doc) {
            DocumentStorage::delete($document->file_doc);
        }
        if ($document->picture) {
            CoverImageStorage::delete($document->picture);
        }

        $document->delete();

        if ($request->input('redirect') === 'public') {
            return redirect()->route('home')->with('success', 'Document supprimé.');
        }

        return redirect()->route('documents.index')->with('message', 'Document supprimé');
    }

    public function desactivateDocument($id)
    {
        Document::findOrFail($id)->update(['statut_publication' => 0]);

        return back();
    }

    public function activateDocument($id)
    {
        Document::findOrFail($id)->update(['statut_publication' => 1]);

        return back();
    }

    public function localisationIndex($id)
    {
        $document = Document::findOrFail($id);

        return view('document.localisation', compact('document'));
    }

    public function addLocalisation(Request $request)
    {
        $request->validate([
            'localisation_id' => 'required|integer|exists:documents,id',
            'localisation' => 'required|string',
        ]);

        Document::where('id', $request->localisation_id)->update([
            'localisation' => $request->localisation,
        ]);

        return redirect()->route('documents.index')->with('message', 'Localisation ajoutée');
    }

    public function removeLocalisation($id)
    {
        Document::findOrFail($id)->update(['localisation' => null]);

        return redirect()->route('documents.index')->with('message', 'Localisation supprimée');
    }

    public function download(Document $document)
    {
        abort_unless($document->file_doc && DocumentStorage::exists($document->file_doc), 404);

        ContentView::record($document, 'download');

        return DocumentStorage::download($document->file_doc, $document->file_doc);
    }

    public function report(Request $request, Document $document)
    {
        $request->validate([
            'message' => 'required|string|min:10|max:2000',
        ]);

        DocumentReport::create([
            'document_id' => $document->id,
            'user_id' => Auth::id(),
            'reporter_name' => Auth::user()->firstname . ' ' . Auth::user()->lastname,
            'reporter_email' => Auth::user()->email,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Signalement enregistré.');
    }
}
