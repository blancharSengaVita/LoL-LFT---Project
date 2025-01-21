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
            x-data="{ showMobileMenu: false }"
            class="relative"
        >
            <!-- Background image as an <img> tag -->
            <img
                src="{{ Vite::asset('resources/images/06_Champions_1_1_qbt1ff/06_Champions_1_1_qbt1ff_c_scale,w_1400.webp') }}"
                sizes="(max-width: 1400px) 100vw, 1400px"
                srcset="
        {{ Vite::asset('resources/images/06_Champions_1_1_qbt1ff/06_Champions_1_1_qbt1ff_c_scale,w_200.webp') }} 200w,
        {{ Vite::asset('resources/images/06_Champions_1_1_qbt1ff/06_Champions_1_1_qbt1ff_c_scale,w_564.webp') }} 564w,
        {{ Vite::asset('resources/images/06_Champions_1_1_qbt1ff/06_Champions_1_1_qbt1ff_c_scale,w_893.webp') }} 893w,
        {{ Vite::asset('resources/images/06_Champions_1_1_qbt1ff/06_Champions_1_1_qbt1ff_c_scale,w_1136.webp') }} 1136w,
        {{ Vite::asset('resources/images/06_Champions_1_1_qbt1ff/06_Champions_1_1_qbt1ff_c_scale,w_1371.webp') }} 1371w,
        {{ Vite::asset('resources/images/06_Champions_1_1_qbt1ff/06_Champions_1_1_qbt1ff_c_scale,w_1400.webp') }} 1400w"
                alt="Hero Image"
                class="absolute inset-0 w-full h-full object-cover opacity-25 filter grayscale-20 blur-20 backdrop-contrast-20 brightness-40"
            >

            <div class="relative isolate px-6 pt-14 lg:px-8">
                <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                    <div class="text-center">
                        <p class="text-4xl font-bold tracking-tight text-gray-600 sm:text-6xl">LoL LFT</p>
                        <p class="mt-6 text-lg leading-8 text-gray-900 ">Trouve les partenaires qui te permettront
                            d’atteindre des sommets</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-hidden bg-white py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2">
                    <div class="lg:pr-8 lg:pt-4">
                        <div class="lg:max-w-lg">
                            <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-600 sm:text-4xl">Trouvez des
                                partenaires idéaux pour vos parties professionnels ou amateurs</h2>
                            <p class="mt-6 text-lg leading-8 text-gray-900 ">Que vous cherchiez à rejoindre une équipe
                                professionnelle pour participer à un championnat, que vous souhaitiez simplement trouver
                                un partenaire pour vous amuser lors de vos parties, ou que vous envisagiez d'aider une
                                équipe en tant que membre du staff, notre plateforme est là pour vous. Nous vous offrons
                                la possibilité de trouver les partenaires idéaux pour concrétiser vos objectifs dans le
                                monde de League of Legends.
                            </p>
                        </div>
                    </div>
                    <div class="border border-gray-600 p-8 rounded-xl w-fit relative">
                        <img srcset="
        {{ Vite::asset('resources/images/lookingForTeammate_jbiwzk/lookingForTeammate_jbiwzk_c_scale,w_190.png') }} 190w,
        {{ Vite::asset('resources/images/lookingForTeammate_jbiwzk/lookingForTeammate_jbiwzk_c_scale,w_903.png') }} 903w,
        {{ Vite::asset('resources/images/lookingForTeammate_jbiwzk/lookingForTeammate_jbiwzk_c_scale,w_1379.png') }} 1379w,
        {{ Vite::asset('resources/images/lookingForTeammate_jbiwzk/lookingForTeammate_jbiwzk_c_scale,w_1802.png') }} 1802w,
        {{ Vite::asset('resources/images/lookingForTeammate_jbiwzk/lookingForTeammate_jbiwzk_c_scale,w_2100.png') }} 2100w"
                             src="{{ Vite::asset('resources/images/lookingForTeammate_jbiwzk/lookingForTeammate_jbiwzk_c_scale,w_2100.png') }}"

                             class="w-[48rem] max-w-none rounded-xl shadow-xl ring-1 ring-gray-400/10 sm:w-[57rem] md:-ml-4 lg:-ml-0"

                             alt="Product screenshot"
                             width="2432" height="1442">
                    </div>

                </div>
            </div>
        </div>
        <div class="overflow-hidden bg-white py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2">
                    <div class="lg:ml-auto lg:pl-4 lg:pt-4">
                        <div class="lg:max-w-lg">
                            <p class="mt-2 text-3xl font-bold tracking-tight text-gray-600 sm:text-4xl">Montrez votre
                                vrai potentiel</p>
                            <p class="mt-6 text-lg leading-8 text-gray-900 ">
                                Créer votre profil sur notre plateforme qui ne sera pas simplement une liste de
                                compétences et d'expériences, mais une représentation visuelle de votre parcours et de
                                vos compétences. Mettez en avant votre classement, vos champions préférés, votre style
                                de jeu et bien plus encore.
                            </p>

                        </div>
                    </div>
                    <div class="flex items-start justify-end lg:order-first">
                        <div class="border border-gray-600 p-8 rounded-xl w-fit relative">
                            <img
                                sizes="(max-width: 1400px) 100vw, 1400px"
                                srcset="
        {{ Vite::asset('resources/images/dashboard_heapjh/dashboard_heapjh_c_scale,w_200.png') }} 200w,
        {{ Vite::asset('resources/images/dashboard_heapjh/dashboard_heapjh_c_scale,w_660.png') }} 660w,
        {{ Vite::asset('resources/images/dashboard_heapjh/dashboard_heapjh_c_scale,w_1012.png') }} 1012w,
        {{ Vite::asset('resources/images/dashboard_heapjh/dashboard_heapjh_c_scale,w_1250.png') }} 1250w,
        {{ Vite::asset('resources/images/dashboard_heapjh/dashboard_heapjh_c_scale,w_1400.png') }} 1400w"
                                src="{{ Vite::asset('resources/images/dashboard_heapjh/dashboard_heapjh_c_scale,w_1400.png') }}" alt="Dashboard screenshot" class="w-[48rem] max-w-none rounded-xl shadow-xl ring-1 ring-gray-400/10 sm:w-[57rem]" width="2432" height="1442">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white py-20 sm:py-28 relative">
            <svg width="153" height="70" viewBox="0 0 153 70" stroke-width="1.2" fill="none" xmlns="http://www.w3.org/2000/svg" class="absolute left-44 bottom-[430px] hidden lg:block">
                <rect x="25.0103" y="10.6969" width="34.3698" height="34.3698" transform="rotate(45 25.0103 10.6969)" stroke="#C8AA6E"/>
                <rect x="75.0103" y="0.707107" width="48.4975" height="48.4975" transform="rotate(45 75.0103 0.707107)" stroke="#C8AA6E"/>
                <rect x="127.01" y="10.6969" width="34.3698" height="34.3698" transform="rotate(45 127.01 10.6969)" stroke="#C8AA6E"/>
            </svg>
            <svg width="153" height="70" viewBox="0 0 153 70" stroke-width="1.2" fill="none" xmlns="http://www.w3.org/2000/svg" class="absolute left-12 top-80 hidden lg:block">
                <rect x="25.0103" y="10.6969" width="34.3698" height="34.3698" transform="rotate(45 25.0103 10.6969)" stroke="#C8AA6E"/>
                <rect x="75.0103" y="0.707107" width="48.4975" height="48.4975" transform="rotate(45 75.0103 0.707107)" stroke="#C8AA6E"/>
                <rect x="127.01" y="10.6969" width="34.3698" height="34.3698" transform="rotate(45 127.01 10.6969)" stroke="#C8AA6E"/>
            </svg>
            <svg width="153" height="70" viewBox="0 0 153 70" stroke-width="1.2" fill="none" xmlns="http://www.w3.org/2000/svg" class="absolute right-20 top-44 hidden lg:block">
                <rect x="25.0103" y="10.6969" width="34.3698" height="34.3698" transform="rotate(45 25.0103 10.6969)" stroke="#C8AA6E"/>
                <rect x="75.0103" y="0.707107" width="48.4975" height="48.4975" transform="rotate(45 75.0103 0.707107)" stroke="#C8AA6E"/>
                <rect x="127.01" y="10.6969" width="34.3698" height="34.3698" transform="rotate(45 127.01 10.6969)" stroke="#C8AA6E"/>
            </svg>
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:text-center">
                    <H2 class="mt-2 text-3xl font-bold tracking-tight text-gray-600 sm:text-4xl">Connectez-vous avec des
                        passionnés partageant les mêmes intérêts</H2>
                    <p class="mt-6 mb-12 text-lg leading-8 text-gray-900 ">Grâce à notre plateforme, vous pouvez vous
                        connecter
                        avec d'autres passionnés de League of Legends qui partagent vos intérêts. Que vous soyez un
                        joueur professionnel ou amateur, un jeune coach ou un coach experimenté, une équipe pour clash
                        ou une équipe LEC, vous trouverez des personnes qui comprennent votre passion pour le jeu et
                        votre ambition.</p>
                </div>
            </div>
        </div>
        <div class="absolute w-full
{{--        bg-[url('../../../../../public/images/footer.jpg')]--}}
{{--            bg-[url('../../../../images/hero.webp')]--}}
{{--            bg-no-repeat bg-cover bg-center--}}
            ">
            {{--            <img--}}
            {{--                src="{{ Vite::asset('resources/images/footer.jpg') }}"--}}
            {{--                alt="Hero Image"--}}
            {{--                class="absolute inset-0 w-full h-full object-cover opacity-35 filter grayscale-20 blur-20 backdrop-contrast-40 brightness-50"--}}
            {{--            >--}}
            <img class="absolute inset-0 w-full h-full object-cover opacity-35 filter grayscale-20 blur-20 backdrop-contrast-40 brightness-50"
                 sizes="(max-width: 1920px) 100vw, 1920px"
                 srcset="
        {{ Vite::asset('resources/images/footer_i7c8ez/footer_i7c8ez_c_scale,w_190.jpg') }} 190w,
        {{ Vite::asset('resources/images/footer_i7c8ez/footer_i7c8ez_c_scale,w_451.jpg') }} 451w,
        {{ Vite::asset('resources/images/footer_i7c8ez/footer_i7c8ez_c_scale,w_639.jpg') }} 639w,
        {{ Vite::asset('resources/images/footer_i7c8ez/footer_i7c8ez_c_scale,w_800.jpg') }} 800w,
        {{ Vite::asset('resources/images/footer_i7c8ez/footer_i7c8ez_c_scale,w_934.jpg') }} 934w,
        {{ Vite::asset('resources/images/footer_i7c8ez/footer_i7c8ez_c_scale,w_1074.jpg') }} 1074w,
        {{ Vite::asset('resources/images/footer_i7c8ez/footer_i7c8ez_c_scale,w_1204.jpg') }} 1204w,
        {{ Vite::asset('resources/images/footer_i7c8ez/footer_i7c8ez_c_scale,w_1317.jpg') }} 1317w,
        {{ Vite::asset('resources/images/footer_i7c8ez/footer_i7c8ez_c_scale,w_1426.jpg') }} 1426w,
        {{ Vite::asset('resources/images/footer_i7c8ez/footer_i7c8ez_c_scale,w_1543.jpg') }} 1543w,
        {{ Vite::asset('resources/images/footer_i7c8ez/footer_i7c8ez_c_scale,w_1659.jpg') }} 1659w,
        {{ Vite::asset('resources/images/footer_i7c8ez/footer_i7c8ez_c_scale,w_1776.jpg') }} 1776w,
        {{ Vite::asset('resources/images/footer_i7c8ez/footer_i7c8ez_c_scale,w_1883.jpg') }} 1883w,
        {{ Vite::asset('resources/images/footer_i7c8ez/footer_i7c8ez_c_scale,w_1920.jpg') }} 1920w"
                 src="{{ Vite::asset('resources/images/footer_i7c8ez/footer_i7c8ez_c_scale,w_1920.jpg') }}"
                 alt="">
            <div class="mx-auto max-w-7xl py-24 sm:px-6 sm:py-32 lg:px-8">
                <div class="relative isolate overflow-hidden bg-gray-900 px-6 pt-16 shadow-2xl sm:rounded-3xl sm:px-16 md:pt-24 lg:flex lg:gap-x-20 lg:px-24 lg:pt-0">
                    <svg viewBox="0 0 1024 1024" class="absolute left-1/2 top-1/2 -z-10 h-[64rem] w-[64rem] -translate-y-1/2 [mask-image:radial-gradient(closest-side,white,transparent)] sm:left-full sm:-ml-80 lg:left-1/2 lg:ml-0 lg:-translate-x-1/2 lg:translate-y-0" aria-hidden="true">
                        <circle cx="512" cy="512" r="512" fill="url(#759c1415-0410-454c-8f7c-9a820de03641)" fill-opacity="0.7"/>
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
                            <a href="{{ route('register')  }}" title="Aller vers la page d'inscription" class="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">S'inscrire</a>
                        </div>
                    </div>
                    <div class="relative mt-16 h-80 lg:mt-8">
                        <img class="absolute left-0 top-0 w-[57rem] max-w-none rounded-md bg-white/5 ring-1 ring-white/10"
                             sizes="(max-width: 1920px) 100vw, 1920px"
                             srcset="
        {{ Vite::asset('resources/images/message_b16d5r/message_b16d5r_c_scale,w_190.png') }} 190w,
        {{ Vite::asset('resources/images/message_b16d5r/message_b16d5r_c_scale,w_1372.png') }} 1372w,
        {{ Vite::asset('resources/images/message_b16d5r/message_b16d5r_c_scale,w_1920.png') }} 1920w"
                             src="{{ Vite::asset('resources/images/message_b16d5r/message_b16d5r_c_scale,w_1920.png') }}"
                             alt="App screenshot" width="1824" height="1080">
                    </div>
                </div>
            </div>
            <div class="mx-auto max-w-7xl px-6 py-12 md:flex md:items-center md:justify-between lg:px-8">
                <div class="flex justify-center space-x-6 md:order-2 z-10">
                    <a href="https://www.instagram.com/blancharsv/" title="Aller vers l'instagram de Blanchar" class="text-gray-400 hover:text-gray-600">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="https://x.com/SparklesSupa" title="aller vers le X de Blanchar" class="text-gray-400 hover:text-gray-600">
                        <span class="sr-only">X</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M13.6823 10.6218L20.2391 3H18.6854L12.9921 9.61788L8.44486 3H3.2002L10.0765 13.0074L3.2002 21H4.75404L10.7663 14.0113L15.5685 21H20.8131L13.6819 10.6218H13.6823ZM11.5541 13.0956L10.8574 12.0991L5.31391 4.16971H7.70053L12.1742 10.5689L12.8709 11.5655L18.6861 19.8835H16.2995L11.5541 13.096V13.0956Z"/>
                        </svg>
                    </a>
                    <a href="https://github.com/blancharSengaVita" title="Aller vers le Github de Blanchar" class="text-gray-400 hover:text-gray-600">
                        <span class="sr-only">GitHub</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
                <div class="mt-8 md:order-1 md:mt-0 z-10">
                    <p class="text-center text-xs leading-5 text-gray-400 ">&copy; 2024 Blanchar Senga-Vita, Inc. Tous
                        droits
                        réservés</p>
                </div>
            </div>
        </div>

    </main>
    {{--    <livewire:partials.footer/>--}}
</div>
