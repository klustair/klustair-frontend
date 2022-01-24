
        @foreach ($vulnerabilities as $vulnerabily)
        <tr>
            <td style="padding: 0.5rem;"><button type="button" class="btn btn-tool collapseDetails"><i class="fas fa-plus"></i></button></td>
            <td><b>{{$vulnerabily['title']}}</b></td>
            <td><nobr><a href="/vulnerability/{{ $vulnerabily['vulnerability_id'] }}">{{$vulnerabily['vulnerability_id']}}</a><nobr></td>
            <td>{{$vulnerabily['pkg_name']}}</td>
            <!-- disabled since trivy is not providing the data 
            <td>
                @isset ($vulnerabily['cvss']['v3']['scores']['base'])
                <div class="progress progress-xs">
                    <div class="progress-bar bg-purple" style="width: {{ $vulnerabily['cvss']['v3']['scores']['base']*10}}%"></div>
                </div>
                <div class="progress progress-xs">
                    <div class="progress-bar bg-fuchsia" style="width: {{ $vulnerabily['cvss']['v3']['scores']['temporal']*10}}%"></div>
                </div>
                <div class="progress progress-xs">
                    <div class="progress-bar bg-info" style="width: {{ $vulnerabily['cvss']['v3']['scores']['environmental']*10}}%"></div>
                </div>
                @endisset
                @if (@isset ($vulnerabily['cvss']['V2Score']) && !@isset ($vulnerabily['cvss']['v3']['scores']['base']))
                <div class="progress progress-xs">
                    <div class="progress-bar bg-purple" style="width: {{ $vulnerabily['cvss']['v2']['scores']['base']*10}}%"></div>
                </div>
                @endif
            </td>
            -->
            <td><span class="badge {{$vulnseverity[$vulnerabily['severity']]}}">{{$vulnerabily['cvss_base_score']}}</span></td>
            <td>
                @if ($vulnerabily['fixed_version'] != "")
                <span class="badge badge-pill badge-success">yes</span>
                @endif
            </td>
            @auth
            <td>
                <input type="checkbox" id="{{ $vulnerabily['vulnerability_id'] }}" class="whitelistItem" name="whitelist" value="{{ $vulnerabily['uid'] }}" @if ($vulnerabily['images_vuln_whitelist_uid'] != "") checked @endif>
            </td>
            @endauth
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
            </td>
        </tr>
        <p>
        @endforeach