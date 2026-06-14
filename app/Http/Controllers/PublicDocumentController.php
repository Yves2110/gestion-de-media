<?php



namespace App\Http\Controllers;



use App\Models\ContentView;

use App\Models\Document;

use App\Models\DocumentReport;

use App\Support\DocumentStorage;
use App\Support\VisitorActivity;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Symfony\Component\HttpFoundation\StreamedResponse;



class PublicDocumentController extends Controller

{

    public function show(Document $document)
    {
        $this->authorizeView($document);

        $document->load('source');

        if ($document->statut_publication) {
            ContentView::record($document, 'view');
            VisitorActivity::recordModel($document, 'view');
        }

        $wordCount = \App\Rules\MinWords::countWords($document->resume ?? '');

        return view('home.document-show', [
            'document' => $document,
            'wordCount' => $wordCount,
            'isPreview' => ! $document->statut_publication,
        ]);
    }



    public function download(Document $document): StreamedResponse

    {

        abort_unless($document->statut_publication, 404);

        abort_unless($document->file_doc && DocumentStorage::exists($document->file_doc), 404);



        ContentView::record($document, 'download');



        return DocumentStorage::download($document->file_doc, $document->file_doc);

    }



    public function report(Request $request, Document $document)

    {

        abort_unless($document->statut_publication, 404);



        $request->validate([

            'message' => 'required|string|min:10|max:2000',

            'reporter_name' => 'nullable|string|max:120',

            'reporter_email' => 'nullable|email|max:190',

        ]);



        DocumentReport::create([

            'document_id' => $document->id,

            'user_id' => Auth::id(),

            'reporter_name' => Auth::check()

                ? Auth::user()->firstname . ' ' . Auth::user()->lastname

                : $request->reporter_name,

            'reporter_email' => Auth::check() ? Auth::user()->email : $request->reporter_email,

            'message' => $request->message,

        ]);



        return back()->with('success', 'Signalement enregistré. Merci pour votre retour.');

    }

    private function authorizeView(Document $document): void
    {
        if ($document->statut_publication) {
            return;
        }

        abort_unless(Auth::check() && Auth::user()->isAdmin(), 404);
    }

}


