
        @foreach ($vulnerabilities as $vulnerabily)
        <tr>
            <td style="display:none">{{$vulnerabily['severity']}}</td>
            <td><a href="vulnerability/{{$vulnerabily['vulnerability_id']}}"><nobr>{{$vulnerabily['vulnerability_id']}}<nobr></a></td>
            <td>
                <b>
                {{$vulnerabily['title']}}
                @if ($vulnerabily['title'] == "")
                {{ Str::limit($vulnerabily['descr'],200) }}
                @endif
                </b>
            </td>
            <td>{{$vulnerabily['pkg_name']}}</td>
            <td>{{$vulnerabily['imagecount']}}</td>
            <td>
                <pre>{{print_r($vulnerabily)}}</pre>
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
            @auth
            <td>
                <input type="checkbox" id="{{ $vulnerabily['vulnerability_id'] }}" class="whitelistItem" name="whitelist" value="{{ $vulnerabily['uid'] }}" @if ($vulnerabily['images_vuln_whitelist_uid'] != "") checked @endif>
            </td>
            @endauth
        </tr>
        <p>
        @endforeach