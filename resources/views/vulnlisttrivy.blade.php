
        @foreach ($vulnerabilities as $vulnerabily)
        <tr>
            <td style="padding: 0.5rem;"><button type="button" class="btn btn-tool collapseDetails"><i class="fas fa-plus"></i></button></td>
            <td><b>{{$vulnerabily['title']}}</b></td>
            <td><nobr>{{$vulnerabily['vulnerability_id']}}<nobr></td>
            <td>{{$vulnerabily['pkg_name']}}</td>
            <td>
                @isset ($vulnerabily['cvss']['V3Score'])
                <div class="progress progress-xs">
                    <div class="progress-bar bg-purple" style="width: {{ $vulnerabily['cvss']['V3Vector_base_score']*10}}%"></div>
                </div>
                <div class="progress progress-xs">
                    <div class="progress-bar bg-fuchsia" style="width: {{ $vulnerabily['cvss']['V3Vector_modified_esc']*10}}%"></div>
                </div>
                <div class="progress progress-xs">
                    <div class="progress-bar bg-info" style="width: {{ $vulnerabily['cvss']['V3Vector_modified_isc']*10}}%"></div>
                </div>
                @endisset
                @if (@isset ($vulnerabily['cvss']['V2Score']) && !@isset ($vulnerabily['cvss']['V3Score']))
                <div class="progress progress-xs">
                    <div class="progress-bar bg-purple" style="width: {{ $vulnerabily['cvss']['V2Vector_base_score']*10}}%"></div>
                </div>
                @endif
            </td>
            <td><span class="badge {{$vulnseverity[$vulnerabily['severity']]}}">{{$vulnerabily['cvss_base_score']}}</span></td>
            <td>
                @if ($vulnerabily['fixed_version'] != "")
                <span class="badge badge-pill badge-success">yes</span>
                @endif
            </td>
            <td>
                <input type="checkbox" id="{{ $vulnerabily['vulnerability_id'] }}" class="whitelistItem" name="whitelist" value="{{ $vulnerabily['uid'] }}" @if ($vulnerabily['images_vuln_whitelist_uid'] != "") checked @endif>
            </td>
        </tr>
        <tr style="display: none">
            <td colspan="8" style="border-top: none">
                <div class="row">
                    <div class="col-sm-7 p-3 bg-light rounded border">
                    {{ $vulnerabily['descr'] }}
                    </div>
                    <div class="col-sm-5">
                        <table>
                            <tr>
                                <th>installed version</th>
                                <td>{{ $vulnerabily['installed_version'] }}</td>
                            </tr>
                            <tr>
                                <th>fixed version</th>
                                <td>{{ $vulnerabily['fixed_version'] }}</td>
                            </tr>
                            <tr>
                                <th>published</th>
                                <td>{{ $vulnerabily['published_date'] }}</td>
                            </tr>
                            <tr>
                                <th>last modified</th>
                                <td>{{ $vulnerabily['last_modified_date'] }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row pt-4">
                    <div class="col-sm-7">
                        @if ($vulnerabily['links'] != '')
                        <ul class="fa-ul" style="max-width: 800px;">
                            @foreach ($vulnerabily['links'] as $link)
                            <li><span class="fa-li" style="color: gray"><i class="fas fa-link fa-xs"></i></span><a href="{{ $link }}" target="_blank">{{ $link }}</a></li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    <div class=col-sm-5>
                        @include('elements.cvss', ['vuln_cvss' => $vulnerabily['cvss']])
                    </div>
                </div>
            </td>
        </tr>
        <p>
        @endforeach