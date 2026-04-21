 <?php

    use App\Models\Entreprise;

    $entreprise = Entreprise::latest()->get();
?>
 <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Information Entreprise') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __("Mettez à jour les informations de votre entreprise, vos coordonnées et votre adresse.") }}
            </p>
        </header>

        @foreach($entreprise as $entreprise)
        <form method="post" action="{{ route('entreprise.eUpdate', $entreprise) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf
            @method('patch')

            <div>
                <x-input-label for="nom" :value="__('Nom')" />
                <x-text-input id="nom" name="nom" readOnly type="text" class="mt-1 block w-full" :value="old('nom', $entreprise->nom)" required autofocus autocomplete="nom" />
                <x-input-error class="mt-2" :messages="$errors->get('nom')" />
            </div>

            <div>
                <x-input-label for="telephone" :value="__('Telephone')" />
                <x-text-input id="telephone" name="telephone" type="text" class="mt-1 block w-full" :value="old('telephone', $entreprise->telephone)" required autocomplete="telephone" />
                <x-input-error class="mt-2" :messages="$errors->get('telephone')" />
            </div>

            <div>
                <x-input-label for="adresse" :value="__('Adresse')" />
                <x-text-input id="" name="adresse" type="text" class="mt-1 block w-full" :value="old('adresse', $entreprise->adresse)" autofocus autocomplete="adresse" />
                <x-input-error class="mt-2" :messages="$errors->get('adresse')" />
            </div>

            <div>
                <x-input-label for="ninea" :value="__('Ninea')" />
                <x-text-input id="" name="ninea" type="text" class="mt-1 block w-full" :value="old('ninea', $entreprise->ninea)" autofocus autocomplete="ninea" />
                <x-input-error class="mt-2" :messages="$errors->get('ninea')" />
            </div>

            <div>
                <x-input-label for="adresse" :value="__('TVA')" />
                <x-text-input id="" name="taux_tva" type="text" class="mt-1 block w-full" :value="old('taux_tva', $entreprise->taux_tva)" autofocus autocomplete="tva" />
                <x-input-error class="mt-2" :messages="$errors->get('taux_tva')" />
            </div>              
            
            <div>
                <x-input-label for="logo" :value="__('Logo')" />
                <img src="{{asset('storage/'.$entreprise->logo)}}" name="logo" class="mt-1 block w-full" style="width: 150px; height: 60px;" alt="Aucun Logo" autofocus autocomplete="logo">

                <x-text-input id="" name="logo" type="file" class="mt-1 block w-full" :value="old('logo', $entreprise->logo)" autofocus autocomplete="logo" />
                <x-input-error class="mt-2" :messages="$errors->get('logo')" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Enregister') }}</x-primary-button>

                @if (session('status') === 'entreprise-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600"
                    >{{ __('enregistrée.') }}</p>
                @endif
            </div>
        </form>
        @endforeach
    </section>