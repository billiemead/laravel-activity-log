@extends('layout/adminangular')

@section('title', 'User Activity')

@section('body-content')
    <h2>All User Activity</h2>
    <section class="user_content">
        <div class="top_content">
            <div class="top_content_left">
                <p class="text_light">Showing @{{response.from}} to @{{response.to}} of @{{response.total}} records</p>
            </div>
    
            <div class="top_content_right">
                <div class="filter_item full_width_param user_list_box">
                    <label>User</label>
                    <input type="text"
                           ng-model="filter.user_id"
                           ng-model-options="{debounce:500}"
                           ng-change="getUsers(filter.user_id)"
                           placeholder="Type name or id">
    
                    <div id="user_list" ng-show="userList.length && filter.user_id">
                        <!---->
                        <div class="single_user" ng-repeat="user in userList" ng-click="onUserSelect(user)">
                            <p>@{{ user.username }} <br>
                                <span class="text_light">@{{ user.email }}</span>
                            </p>
                        </div>
                        <!---->
                    </div>
    
                </div>
                <div class="filter_item">
                    <label>Log Type</label>
    
                    <select ng-model="filter.log_type">
                        @foreach(['create','edit','delete','login','lockout'] as $type)
                            <option value="{{ $type}}">{{ $type}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter_item">
                    <label>Table</label>
                    <select ng-model="filter.table">
                        @foreach($tables as $table)
                            <option value="{{ $table}}">{{ $table}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter_item">
                    <label>Date From</label>
                    <date-input ng-model="filter.from_date"></date-input>
                </div>
                <div class="filter_item">
                    <label>Date To</label>
                    <date-input ng-model="filter.to_date"></date-input>
                </div>
                <div class="filter_item" style="justify-content: flex-end;">
                    <button class="btn_reset" ng-show="activeFilter" ng-click="resetParam()">RESET</button>
                </div>
                <div class="filter_item" style="justify-content: flex-end;">
                    <button class="btn_filter" ng-class="{btn_filter_active : activeFilter == true}"
                            ng-click="filterData(filter)">FILTER
                    </button>
                </div>
            </div>
        </div>
    
        <div class="log_data_wrapper">
            <div class="loader" ng-show="isLoading">
                <div class="spinner">
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                </div>
            </div>
            <div class="responsive_table">
                <table>
                    <thead>
                    <tr>
                        <td width="30">ID</td>
                        <td width="260">ACTIVITY DATE</td>
                        <td width="170">LOG TYPE</td>
                        <td>USER</td>
                        <td class="text_right" style="padding-right: 10px;">ACTION</td>
                    </tr>
                    </thead>
    
                    <tr ng-repeat="log in data |filter: selectedType track by $index">
                        <td>@{{ log.id }}</td>
                        <td>
                            @{{ log.log_date }} - @{{log.dateHumanize}}
                        </td>
                        <td ng-switch="log.log_type">
                            <span ng-switch-when="delete|lockout"
                                  ng-switch-when-separator="|" class="badge emergency">@{{log.log_type}}</span>
    
                            <span ng-switch-when="create" class="badge info">@{{log.log_type}}</span>
                            <span ng-switch-when="create" class="lbl_table">to @{{log.table_name}}</span>
                            <span ng-switch-when="edit" class="badge warning edit_badge">@{{log.log_type}}</span>
    
                            <span ng-switch-when="edit|delete"
                                  ng-switch-when-separator="|" class="lbl_table">from @{{ log.table_name }}</span>
    
                            <span ng-switch-default class="badge debug">@{{log.log_type}}</span>
                        </td>
    
                        <td>
                            <strong>username: @{{ log.user.username }}</strong><br />
                            <span class="text_user">email: @{{ log.user.email }}</span>
                        </td>
                        <td class="action_column text_right">
                            <button class="btn_show" ng-click="showPopup(log)">SHOW</button>
                        </td>
                    </tr>
                </table>
    
            </div>
        </div>
        <footer>
            <div></div>
            <div class="footer_right">
            <span class="text_light">Delete data older than {{ config('user-activity.delete_limit') }} days</span>
                <button class="btn" ng-click="deleteLog()">DELETE</button>
            </div>
        </footer>
    
        <div class="popup_wrapper" ng-show="popup">
            <div class="popup" style="width: 60%">
                <div class="header">
                    <div class="title">Log Preview</div>
                    <div class="close" ng-click="popup=false">x</div>
                </div>
                <div class="popup_content">
                    <table style="width: 96%;border: 1px solid #ddd;">
                        <thead>
                        <tr>
                            <td colspan="2">INFO</td>
                        </tr>
                        </thead>
                        <tr>
                            <td class="field_cell_border">type</td>
                            <td class="data_cell_border" ng-switch="selected.log_type">
                                <span ng-switch-when="delete|lockout"
                                      ng-switch-when-separator="|" class="badge emergency">@{{selected.log_type}}</span>
    
                                <span ng-switch-when="create" class="badge info">@{{selected.log_type}}</span>
                                <span ng-switch-when="edit" class="badge warning edit_badge">@{{selected.log_type}}</span>
                                <span ng-switch-default class="badge debug">@{{selected.log_type}}</span>
                            </td>
                        </tr>
                        <tr ng-show="['create','edit','delete'].includes(selected.log_type)">
                            <td class="field_cell_border">table</td>
                            <td class="data_cell_border">@{{ selected.table_name }}</td>
                        </tr>
                        <tr>
                            <td class="field_cell_border">activity time</td>
                            <td class="data_cell_border">@{{ selected.dateHumanize }} - @{{ selected.log_date }}</td>
                        </tr>
                        <tr>
                            <td class="field_cell_border">user</td>
                            <td class="data_cell_border">user name: @{{ selected.user.username }} 
                                <br />
                                email: <span class="text_light">@{{ selected.user.email }}</span></td>
                        </tr>
                    </table>
    
    
                    <br>
    
                    <div class="responsive_table">
                        
                        <table style="width: 96%;border: 1px solid #ddd;">
                            <thead>
                                <tr>
                                    <td colspan="3" ng-show="selected.log_type==='edit'" class="whitebkgd">EDITED DATA = <div class="yellowsquare"></div></td> 
                                </tr>
                            <tr>
                                <td>@{{ ['edit','delete'].includes(selected.log_type)?'FIELD':'' }}</td>
                                <td>@{{ selected.log_type==='edit'?'PREVIOUS':'DATA' }}</td>
                                <td ng-show="selected.log_type==='edit'">CURRENT</td>
                            </tr>
                            </thead>
                            <tbody>
    
                            <tr ng-repeat="(field,value) in selected.json_data">
                                <td class="field_cell_border">@{{ field }}</td>
                                <td class="data_cell_border">@{{ value }}</td>
                                <td class="data_cell_border" ng-show="selected.log_type==='edit'" ng-class="value!=currentData[field]?'changed':''">
                                    @{{ currentData[field] }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
    
                    <br>
                    <div class="responsive_table" ng-if="selected.log_type==='edit' && editHistory.length > 0">
                        <p style="color: #666;;">Another <strong>@{{ editHistory.length }}</strong> edit history found!</p>
                        <table style="width: 96%;border: 1px solid #ddd;">
                            <thead>
                            <tr>
                                <td>time</td>
                                <td>user</td>
                                <td>data</td>
                            </tr>
                            </thead>
                            <tbody>
    
                            <tr ng-repeat="h in editHistory">
                                <td>@{{ h.dateHumanize }}</td>
                                <td>@{{ h.user.username }}</td>
                                <td style="overflow: hidden">@{{ h.data }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
    
                </div>
                <div class="footer">
                    <div>
    
                    </div>
                    <div>
    
                    </div>
                </div>
            </div>
        </div>
        @include('LaravelUserActivity::partials.script')
    </section>
@endsection

@section('footer-content')
    <div class="pagination_wrapper">
        <div paging
            page="response.current_page"
            page-size="response.per_page"
            total="response.total"
            paging-action="init(page)"
            scroll-top="true"
            hide-if-empty="true"
            show-prev-next="true"
            show-first-last="true">
        </div>
    </div>
@endsection