<?php

use function Livewire\Volt\layout;
use function Livewire\Volt\{state};

layout('layouts.guest');

?>


<div>
    <x-slot name="h1">
        Lol LFT
    </x-slot>
    <livewire:partials.guest-header/>
    <main>
        <div
            x-data="{showMobileMenu : false}"
            class="bg-white"
        >
            <div class="relative isolate px-6 pt-14 lg:px-8">
                <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
                    <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
                </div>
                <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                    <div class="text-center">
                        <p class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">LoL LFT</p>
                        <p class="mt-6 text-lg leading-8 text-gray-600">Trouvez les partenaires qui te permettront
                            d’atteindre des sommets</p>
                    </div>
                </div>
                <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
                    <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
                </div>
            </div>
        </div>
        <div class="overflow-hidden bg-white py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2">
                    <div class="lg:pr-8 lg:pt-4">
                        <div class="lg:max-w-lg">
                            <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Trouvez des
                                partenaires idéaux pour vos parties professionnels ou amateurs</h2>
                            <p class="mt-6 text-lg leading-8 text-gray-600">Que vous cherchiez à rejoindre une équipe professionnelle pour participer à un championnat, que vous souhaitiez simplement trouver un partenaire pour vous amuser lors de vos parties, ou que vous envisagiez d'aider une équipe en tant que membre du staff, notre plateforme est là pour vous. Nous vous offrons la possibilité de trouver les partenaires idéaux pour concrétiser vos objectifs dans le monde de League of Legends.
                            </p>
                        </div>
                    </div>
                    <img src="https://tailwindui.com/img/component-images/dark-project-app-screenshot.png" alt="Product screenshot" class="w-[48rem] max-w-none rounded-xl shadow-xl ring-1 ring-gray-400/10 sm:w-[57rem] md:-ml-4 lg:-ml-0" width="2432" height="1442">
                </div>
            </div>
        </div>
        <div class="overflow-hidden bg-white py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2">
                    <div class="lg:ml-auto lg:pl-4 lg:pt-4">
                        <div class="lg:max-w-lg">
                            <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Montrez votre
                                vrai potentiel</p>
                            <p class="mt-6 text-lg leading-8 text-gray-600">
                                Créer votre profil sur notre plateforme qui ne sera pas simplement une liste de
                                compétences et d'expériences, mais une représentation visuelle de votre parcours et de
                                vos compétences. Mettez en avant votre classement, vos champions préférés, votre style
                                de jeu et bien plus encore.
                            </p>

                        </div>
                    </div>
                    <div class="flex items-start justify-end lg:order-first">
                        <img src="https://tailwindui.com/img/component-images/dark-project-app-screenshot.png" alt="Product screenshot" class="w-[48rem] max-w-none rounded-xl shadow-xl ring-1 ring-gray-400/10 sm:w-[57rem]" width="2432" height="1442">
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:text-center">
                    <H2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Connectez-vous avec des
                        passionnés partageant les mêmes intérêts</H2>
                    <p class="mt-6 text-lg leading-8 text-gray-600">Grâce à notre plateforme, vous pouvez vous connecter
                        avec d'autres passionnés de League of Legends qui partagent vos intérêts. Que vous soyez un
                        joueur professionnel ou amateur, un jeune coach ou un coach experimenté, une équipe pour clash
                        ou une équipe LEC, vous trouverez des personnes qui comprennent votre passion pour le jeu et
                        votre ambition.</p>
                </div>
            </div>
        </div>
        <div class="bg-white">
            <div class="mx-auto max-w-7xl py-24 sm:px-6 sm:py-32 lg:px-8">
                <div class="relative isolate overflow-hidden bg-gray-900 px-6 pt-16 shadow-2xl sm:rounded-3xl sm:px-16 md:pt-24 lg:flex lg:gap-x-20 lg:px-24 lg:pt-0">
                    <svg viewBox="0 0 1024 1024" class="absolute left-1/2 top-1/2 -z-10 h-[64rem] w-[64rem] -translate-y-1/2 [mask-image:radial-gradient(closest-side,white,transparent)] sm:left-full sm:-ml-80 lg:left-1/2 lg:ml-0 lg:-translate-x-1/2 lg:translate-y-0" aria-hidden="true">
                        <circle cx="512" cy="512" r="512" fill="url(#759c1415-0410-454c-8f7c-9a820de03641)" fill-opacity="0.7"/>
                        <defs>
                            <radialGradient id="759c1415-0410-454c-8f7c-9a820de03641">
                                <stop stop-color="#7775D6"/>
                                <stop offset="1" stop-color="#E935C1"/>
                            </radialGradient>
                        </defs>
                    </svg>
                    <div class="mx-auto max-w-md text-center lg:mx-0 lg:flex-auto lg:py-32 lg:text-left">
                        <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Prêt à booster votre
                            carrière avec nous </h2>
                        <p class="mt-6 text-lg leading-8 text-gray-300">
                            Rejoignez dès maintenant notre communauté de passionnés et transformez votre passion en une
                            véritable réussite professionnelle. Cliquez ci-dessous pour vous inscrire et commencez votre
                            voyage vers le succès !
                        </p>
                        <div class="mt-10 flex items-center justify-center gap-x-6 lg:justify-start">
                            <a href="{{ route('register')  }}" class="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">S'inscrire</a>
                        </div>
                    </div>
                    <div class="relative mt-16 h-80 lg:mt-8">
                        <img class="absolute left-0 top-0 w-[57rem] max-w-none rounded-md bg-white/5 ring-1 ring-white/10" src="https://tailwindui.com/img/component-images/dark-project-app-screenshot.png" alt="App screenshot" width="1824" height="1080">
                    </div>
                </div>
            </div>
        </div>

    </main>
    <livewire:partials.footer/>
</div>
