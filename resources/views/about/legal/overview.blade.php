@extends('layouts.app')

@section('content')

@include('layouts.partials.banner', [
    'title' => trans('about.legal_overview'),
    'tabs'  => true,
    'breadcrumbs' => [
        '/'     => trans('about.home'),
        '/docs' => trans('about.documentation'),
        '#'     => trans('about.legal_overview')
    ]
])

    <div class="container">

        <div class="tabs is-centered">
            <ul>
                <li class="is-active"><a href="{{ route('legal') }}">{{ trans('about.legal_overview') }}</a></li>
                <li><a href="{{ route('eula') }}">{{ trans('about.eula_title') }}</a></li>
                <li><a href="{{ route('license') }}">{{ trans('about.license_title') }}</a></li>
                <li><a href="{{ route('privacy_policy') }}">{{ trans('about.privacy_policy_title') }}</a></li>
            </ul>
        </div>

        <section class="box">

            <h3 class="is-size-4">Guiding Principles</h3>

            <p>These guiding principles form the basis of this agreement:
            <br> 1) Honor the intellectual property and copyrights that protect the Licensors of Bible Content.
            <br> 2) Provide the Content in a way that brings glory to God.
            <br> 3) Get God’s Word to every person.</p>

            <h3 class="is-size-4">End User License Agreement (EULA)</h3>

            <p>The Digital Bible Platform (DBP) is a standardized web-based system that allows access to and management of Bible Content using multiple media, formats, and partners. The vision of the DBP is to combine the best technology with the richest Bible Content available in one usable, accessible platform, to reach audiences around the world with the truth of Scripture in every available language.</p>
            <p>The DBP is made possible by strong partnerships between Hosanna/Faith Comes By Hearing , Bible Agencies and Bible Cause donors. The DBP’s Application Programming Interface (API) allows approved USERS (you) to integrate its database of rich Bible content with your software, applications, and/or websites. These services are free to use and available to approved users.</p>

        </section>
        {{--
                <section class="box">

                    Ownership

                    The DBP and DBP API are owned by Hosanna/Faith Comes By Hearing . The Bible Content housed in the DBP is owned by Hosanna/Faith Comes By Hearing and its Licensor-Partners.

                    Developer Codes and Access
                    If approved, you will be assigned a developer key to access the API and the Content. You are responsible for maintaining the confidentiality of your information, including your developer key.
                    You may not allow others to use your developer key and you are responsible for all uses of your developer key. Please notify the DBP immediately of any unauthorized use of your developer key.

                    Terms and Conditions
                    The Terms and Conditions outlined in this document form a binding agreement between you and Hosanna/Faith Comes By Hearing and apply to all users of the DBP. The terms may be amended from time to time. Your use of the DBP and any of its content indicates your acceptance of these terms. You agree to use the API at your own risk. Hosanna/Faith Comes By Hearing will retain full ownership of the DBP, the DBP API and any code that the DBP creates to generate or display Content. Hosanna/Faith Comes By Hearing , together with its Licensors as applicable, will retain full ownership of the Content as specified in separate agreements. The Content includes the audio, scripts, text, graphics, photos, sounds, music, videos, audiovisual combinations, interactive features and other materials accessed through the DBP API. Hosanna/Faith Comes By Hearing reserves the right to modify the Terms and Conditions of this Agreement by posting a revision on this webpage or by otherwise making such revision available for your review. Your continued use of the DBP API constitutes your agreement to any such revisions.

                    Grant
                    Hosanna/Faith Comes By Hearing grants you limited, non-exclusive, non-transferable, non-sublicensable, revocable permission to access and display in your software Application certain DBP Content available through the DBP API. You agree to retain all copyright, trademarks, service marks and other proprietary notices contained on the Content or DBP materials. You also agree to clearly and conspicuously attribute the source of all Content as received from www.Bible.is You agree not to sublicense, distribute, rent, sell, transfer rights or use the DBP and DBP Content outside the scope outlined in this Agreement. You agree to retain and display functioning links to www.Bible.is or to third-party applications or websites provided within the Content. The Content is provided to you AS IS and may not be changed in any way. Any other use of the Content would require the written consent of Hosanna/Faith Comes By Hearing and, as applicable, its Licensor-Partners.

                    Commercial Restrictions
                    You may not use the DBP API to create Applications that are offered at a cost to the end user. You may not use the DBP API to offer or promote services that are damaging or detrimental to Hosanna/Faith Comes By Hearing or its licensors, licensees, affiliates and partners. You may not share the DBP, the DBP API or its Content with any third parties as an aggregator or third party delivery mechanism, whether for commercial or non-commercial purposes.

                    Financial Considerations
                    The DBP API is available for you to use at no cost in terms of bandwidth, storage, maintenance and other costs. You acknowledge and agree by acceptance of this Agreement that you are willing to receive solicitation to support the DBP as a donor. In the event your use of the DBP API exceeds normal business use as defined by Hosanna/Faith Comes By Hearing , we may contact you to support the costs in proportion to your usage.

                    Modifications
                    We may release new versions of the DBP API and may require you to obtain and use the most recent version.

                    DBP Access
                    You agree to provide DBP with access to your Application and other materials related to your use of the DBP API as reasonably requested by DBP, in order to allow us to verify your compliance with this Agreement. You agree that we may crawl or otherwise monitor your online Applications for this purpose.

                    Technical Support
                    Hosanna/Faith Comes By Hearing bears no obligation to provide you or your users with support, software upgrades, enhancements or modifications. You agree that you are responsible for providing user support and other technical assistance for your Application.

                    Warranty and Liability
                    To the fullest extent permitted by applicable law, Hosanna/Faith Comes By Hearing makes no warranty of any kind, including without limitation warranties of merchantability, fitness for a particular use and non-infringement. To the extent permitted by applicable law, Hosanna/Faith Comes By Hearing will not be liable for your lost revenues or damages.

                    Termination
                    Hosanna/Faith Comes By Hearing may, within reason, terminate, suspend, limit access to, update or alter all or any part of the DBP. By permitting you access to the DBP and the Content, Hosanna/Faith Comes By Hearing does not convey any interest in or to the DBP and Content. All right, title and interest in and to the DBP is and shall remain in Hosanna/Faith Comes By Hearing .

                    Indemnification
                    You agree to defend, indemnify and hold harmless Hosanna/Faith Comes By Hearing (and its wholly owned subsidiaries, its officers, directors, employees and agents) from and against any third party claims, actions or demands (including, without limitation, costs, damages and reasonable legal and accounting fees) alleging or resulting from or in connection with your use of the DBP and its Content, or your breach of this Agreement. Hosanna/Faith Comes By Hearing shall use reasonable efforts to provide you prompt notice of any such claim, suit, or proceeding and may assist you, at your expense, in defending any such claim.
        </section>
        --}}
    </div>

@endsection