# Common HTTP Status Codes

|Status Code|Name|Description|
| --- | --- | --- |
| 200 | OK | The request is successful. This status code is generally used for GET and POST requests. |
| 400 | Bad Request | The client request has a syntax error or a parameter error that the server cannot understand. |
| 401 | Unauthorized | Request for authentication failed. |
| 403 | Forbidden | You don’t have permission to execute this request, for example, enterprise authentication failed. |
| 406 | Not Acceptable | Parameter verification failed. |
| 429 | Too Many Requests | The request is too frequent and exceeds the server limit. |
| 500 | Internal Server Error | Internal server error and unable to complete request |
| 501 | Not Implemented | The server does not support the requested function and cannot complete the request. |
| 502 |	Bad Gateway | The server, acting as a gateway or proxy, received an invalid response from the downstream services. | 
| 503 | Service Unavailable | The server is running abnormally and cannot process the request temporarily. |
| 504 |	Gateway Timeout	| When the server acts as a gateway or proxy, it fails to obtain a response from the upstream server timely. |

For detailed API parameter information, request examples, and error descriptions, see the specific API document.

# Obtain player list

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/list:
    get:
      summary: Obtain player list
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for obtaining the player list information.

        2. Sub-accounts can only manage data within their specific workgroup and
        its sub-workgroups.

        3. Basic interface.

        :::
      tags:
        - VNNOX/Player/Player Management
      parameters:
        - name: count
          in: query
          description: >-
            The number of items to read each time, which defaults to 20 and
            ranges from 1 to 100.
          required: false
          example: 20
          schema:
            type: integer
        - name: start
          in: query
          description: From which item to start reading, 0 by default.
          required: false
          example: 0
          schema:
            type: integer
        - name: name
          in: query
          description: Search by the key words of player names.
          required: false
          schema:
            type: string
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  pageInfo:
                    type: object
                    properties:
                      start:
                        type: integer
                        description: From which item to start reading.
                      count:
                        type: integer
                        description: The number of records to read each time.
                    required:
                      - start
                      - count
                    x-apifox-orders:
                      - start
                      - count
                    description: Paging information
                  total:
                    type: integer
                    description: Total records
                  rows:
                    type: array
                    items:
                      type: object
                      properties:
                        playerId:
                          type: string
                          description: Player ID
                        playerType:
                          type: integer
                          description: >-
                            Player type, 1-Synchronous player, 2-Asynchronous
                            player.
                        name:
                          type: string
                          description: Player name
                        sn:
                          type: string
                          description: >-
                            Unique ID of a player. Null denotes that the player
                            is not bound.
                        version:
                          type: string
                          description: Current player version
                        ip:
                          type: string
                          description: Player IP address
                        lastOnlineTime:
                          type: string
                          description: Last heartbeat time of the player
                        onlineStatus:
                          type: integer
                          description: Player status, 0-Offline, 1-Online.
                      x-apifox-orders:
                        - playerId
                        - playerType
                        - name
                        - sn
                        - version
                        - ip
                        - lastOnlineTime
                        - onlineStatus
                      required:
                        - playerId
                        - playerType
                        - name
                        - sn
                        - version
                        - ip
                        - lastOnlineTime
                        - onlineStatus
                required:
                  - pageInfo
                  - total
                  - rows
                x-apifox-orders:
                  - pageInfo
                  - total
                  - rows
              example:
                pageInfo:
                  start: 0
                  count: 20
                total: 7
                rows:
                  - playerId: 7fd6783109670b52103c5bab659701d5
                    playerType: 2
                    name: Taurus-50000983-2
                    sn: BZSA07313J0350000983
                    version: V2.2.0.0101
                    ip: 10.10.11.104
                    lastOnlineTime: '2020-09-09 15:25:32'
                    onlineStatus: 1
          headers: {}
          x-apifox-name: OK
      security: []
      x-apifox-folder: VNNOX/Player/Player Management
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-180498654-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```

# Obtaining Basic Player Information

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/current/online-status:
    post:
      summary: Obtaining Basic Player Information
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for obtaining the current player status
        (online/offline), player type, screen resolution, system version, IP
        address, etc.

        2. Sub-accounts can only manage data within their specific workgroup and
        its sub-workgroups.

        3. Basic interface.

        :::
      tags:
        - VNNOX/Player/Obtaining Player Status
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                  description: >-
                    A collection of player IDs to be handled. At most 100 player
                    IDs can be handled simultaneously.
                playerSns:
                  type: array
                  items:
                    type: string
                  description: >-
                    A maximum of 100 players can be processed at the same time.
                    With playerIds, this parameter is mandatory
              x-apifox-orders:
                - playerIds
                - playerSns
              required:
                - playerIds
                - playerSns
            example: "{\r\n    \"playerIds\": [\r\n        \"8208967d40e9980bab6d12367dc88e0b\"\r\n    ]\r\n}\r\nor\r\n{\r\n    \"playerSns\": [\r\n        \"2KKA02B12N0A10000151\"\r\n    ]\r\n}"
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                    playerId:
                      type: string
                      description: Player ID
                    sn:
                      type: string
                      description: >-
                        Unique ID of a player. Null denotes that the player is
                        not bound.
                    onlineStatus:
                      type: integer
                      description: Player status, 0-Offline, 1-Online.
                    lastOnlineTime:
                      type: string
                      description: Last heartbeat time of the player
                  x-apifox-orders:
                    - playerId
                    - sn
                    - onlineStatus
                    - lastOnlineTime
              example:
                - playerId: 130c0a9f4cd9f2c9d421a97b0293e5d6
                  sn: BZSA07216J0550000373
                  onlineStatus: 1
                  lastOnlineTime: '2020-09-09 14:48:50'
          headers: {}
          x-apifox-name: OK
      security: []
      x-apifox-folder: VNNOX/Player/Obtaining Player Status
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-180498661-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Obtaining Player Configuration Status

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/current/running-status:
    post:
      summary: Obtaining Player Configuration Status
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for asynchronously obtaining the current
        volume, brightness, video source, time zone and time of a player.

        2. Sub-accounts can only manage data within their specific workgroup and
        its sub-workgroups.

        3. Advanced interface.

        :::
      tags:
        - VNNOX/Player/Obtaining Player Status
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                  description: >-
                    A collection of player IDs to be handled. At most 100 player
                    IDs can be handled simultaneously.
                commands:
                  type: array
                  items:
                    type: string
                  description: >-
                    A collection of commands to be obtained volumeValue -Current
                    volume brightnessValue - Current brightness videoSourceValue
                    - Current video source timeValue - Current time zone and
                    time screenPowerStatus - Current screen status
                    syncPlayStatus - Current status of synchronous playback
                    powerStatus - Current multifunction card power status
                noticeUrl:
                  type: string
                  description: >-
                    Result notification interface. The customer interface must
                    return the “ok” string, otherwise the system will try again.
              x-apifox-orders:
                - playerIds
                - commands
                - noticeUrl
              required:
                - playerIds
                - commands
                - noticeUrl
            example:
              playerIds:
                - 8208967d40e9980bab6d12367dc88e0b
              commands:
                - volumeValue
                - brightnessValue
                - videoSourceValue
                - timeValue
              noticeUrl: http://www.abc.com/notice
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are successfully sent
                  fail:
                    type: array
                    items:
                      type: string
                    description: "\tA collection of player IDs that are not sent successfully"
                x-apifox-orders:
                  - success
                  - fail
                required:
                  - success
                  - fail
              example:
                success:
                  - 553cbfe2ff4ad2e0d6bd89bb2c4e85e2
                fail: []
          headers: {}
          x-apifox-name: OK
        x-200:Obtaining Current Time Zone and Time Call-back Arguments:
          description: ''
          content:
            application/json:
              schema:
                title: ''
                type: object
                properties:
                  playerId:
                    type: string
                    description: Corresponding player ID
                  command:
                    type: string
                    description: videoSourceValue
                  data:
                    type: object
                    properties:
                      timeZone:
                        type: string
                        description: Current time zone of a player
                      currentTime:
                        type: string
                        description: Current time of a player
                    x-apifox-orders:
                      - timeZone
                      - currentTime
                    required:
                      - timeZone
                      - currentTime
                    description: Specific content
                x-apifox-orders:
                  - playerId
                  - command
                  - data
                required:
                  - playerId
                  - command
                  - data
              example:
                playerId: 553cbfe2ff4ad2e0d6bd89bb2c4e85e2
                command: timeValue
                logid: 1599635824372
                data:
                  currentTime: '2020-07-03 13:53:26'
                  timeZone: Asia/Shanghai
          headers: {}
          x-apifox-name: Obtaining Current Time Zone and Time Call-back Arguments
        x-200:Obtaining Current Video Source Call-back Arguments:
          description: ''
          content:
            application/json:
              schema:
                title: ''
                type: object
                properties:
                  playerId:
                    type: string
                    description: Corresponding player ID
                  command:
                    type: string
                    description: videoSourceValue
                  data:
                    type: object
                    properties:
                      videoSource:
                        type: integer
                        description: 0-Internal source, 1-External source
                    x-apifox-orders:
                      - videoSource
                    required:
                      - videoSource
                    description: Specific content
                x-apifox-orders:
                  - playerId
                  - command
                  - data
                required:
                  - playerId
                  - command
                  - data
              example:
                playerId: 553cbfe2ff4ad2e0d6bd89bb2c4e85e2
                command: videoSourceValue
                logid: 1599635825372
                data:
                  videoSource: 0
          headers: {}
          x-apifox-name: Obtaining Current Video Source Call-back Arguments
        x-200:Obtaining Current Volume Call-back Arguments:
          description: ''
          content:
            application/json:
              schema:
                title: ''
                type: object
                properties:
                  playerId:
                    type: string
                    description: Corresponding player ID
                  command:
                    type: string
                    description: volumeValue
                  data:
                    type: object
                    properties:
                      ratio:
                        type: integer
                        description: Volume (percentage)
                    x-apifox-orders:
                      - ratio
                    required:
                      - ratio
                    description: Specific content
                x-apifox-orders:
                  - playerId
                  - command
                  - data
                required:
                  - playerId
                  - command
                  - data
              example:
                playerId: 553cbfe2ff4ad2e0d6bd89bb2c4e85e2
                command: volumeValue
                logid: 1599635825372
                data:
                  ratio: 51
          headers: {}
          x-apifox-name: Obtaining Current Volume Call-back Arguments
        x-200:Obtaining Current Brightness Call-back Arguments:
          description: ''
          content:
            application/json:
              schema:
                title: ''
                type: object
                properties:
                  playerId:
                    type: string
                    description: Corresponding player ID
                  command:
                    type: string
                    description: brightnessValue
                  data:
                    type: object
                    properties:
                      ratio:
                        type: integer
                        description: Brightness (percentage)
                    x-apifox-orders:
                      - ratio
                    description: Specific content
                    required:
                      - ratio
                x-apifox-orders:
                  - playerId
                  - command
                  - data
                required:
                  - playerId
                  - command
                  - data
              example:
                playerId: 553cbfe2ff4ad2e0d6bd89bb2c4e85e2
                command: brightnessValue
                logid: 1589247405031
                data:
                  ratio: '69.0'
          headers: {}
          x-apifox-name: Obtaining Current Brightness Call-back Arguments
        x-200:Obtaining Current Screen Status Call-back Arguments:
          description: ''
          content:
            application/json:
              schema:
                title: ''
                type: object
                properties:
                  playerId:
                    type: string
                    description: Corresponding player ID
                  command:
                    type: string
                    description: brightnessValue
                  data:
                    type: object
                    properties:
                      state:
                        type: string
                        description: 'Screen status: CLOSE-Blckout, OPEN-Screen on'
                    x-apifox-orders:
                      - state
                    description: Specific content
                    required:
                      - state
                x-apifox-orders:
                  - playerId
                  - command
                  - data
                required:
                  - playerId
                  - command
                  - data
              example:
                playerId: 0c322adf84bfaba559ec17afdc030617
                command: screenPowerStatus
                logid: 1589226405031
                data:
                  state: CLOSE
          headers: {}
          x-apifox-name: Obtaining Current Screen Status Call-back Arguments
        x-200:Obtaining Current Synchronous Playback Status Call-back Arguments:
          description: ''
          content:
            application/json:
              schema:
                title: ''
                type: object
                properties:
                  playerId:
                    type: string
                    description: Corresponding player ID
                  command:
                    type: string
                    description: brightnessValue
                  data:
                    type: object
                    properties:
                      enable:
                        type: boolean
                        description: 'Synchronous playback status: true-On, false-Off'
                    x-apifox-orders:
                      - enable
                    description: Specific content
                    required:
                      - enable
                x-apifox-orders:
                  - playerId
                  - command
                  - data
                required:
                  - playerId
                  - command
                  - data
              example:
                playerId: b02020f2c50fc4e7a21db193d9085bb1
                command: syncPlayStatus
                logid: 1599550965767
                data:
                  enable: false
          headers: {}
          x-apifox-name: Obtaining Current Synchronous Playback Status Call-back Arguments
        x-200:Obtaining Current Multifunction Card Power Status Call-back Arguments:
          description: ''
          content:
            application/json:
              schema:
                title: ''
                type: object
                properties:
                  playerId:
                    type: string
                    description: Corresponding player ID
                  command:
                    type: string
                    description: brightnessValue
                  logid:
                    type: integer
                  data:
                    type: object
                    properties:
                      current_status_info:
                        type: array
                        items:
                          type: object
                          properties:
                            updatePowerIndexStates:
                              type: array
                              items:
                                type: object
                                properties:
                                  powerIndex:
                                    type: integer
                                    description: Number of power supplies
                                  type:
                                    type: string
                                    description: Configured power label name
                                  status:
                                    type: integer
                                    description: 'Power status: 0-On，1-Off'
                                x-apifox-orders:
                                  - powerIndex
                                  - type
                                  - status
                                required:
                                  - powerIndex
                                  - type
                                  - status
                              description: Power status configuration
                          x-apifox-orders:
                            - updatePowerIndexStates
                          required:
                            - updatePowerIndexStates
                    x-apifox-orders:
                      - current_status_info
                    required:
                      - current_status_info
                    description: Specific content
                x-apifox-orders:
                  - playerId
                  - command
                  - logid
                  - data
                required:
                  - playerId
                  - command
                  - logid
                  - data
              example:
                playerId: b02020f2c50fc4e7a21db193d9085bb1
                command: powerStatus
                logid: 1599554549311
                data:
                  current_status_info:
                    - updatePowerIndexStates:
                        - powerIndex: 0
                          type: Screen power
                          status: 1
                        - powerIndex: 1
                          type: Screen power
                          status: 1
          headers: {}
          x-apifox-name: >-
            Obtaining Current Multifunction Card Power Status Call-back
            Arguments
      security: []
      x-apifox-folder: VNNOX/Player/Obtaining Player Status
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-186309730-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Single-Page Emergency Insertion Solutions

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/emergency-program/page:
    post:
      summary: Single-Page Emergency Insertion Solutions
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for making emergency insertion solutions and
        publishing them to players.

        2. Sub-accounts can only manage data within their specific workgroup and
        its sub-workgroups.

        3. Advanced interface.

        :::
      tags:
        - VNNOX/Solutions/Emergency Insertion
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                    description: player IDs
                  description: >-
                    A collection of player IDs to be handled. At most 100 player
                    IDs can be handled simultaneously.
                attribute:
                  type: object
                  properties:
                    spotsType:
                      type: string
                      description: >-
                        Emergency insertion type, IMMEDIATELY: Immediate
                        insertion, TIMING: Scheduled insertion.
                    normalProgramStatus:
                      type: string
                      description: >-
                        Playback status of a common solution,
                        NORMAL-Normal，PAUSE -Paused.
                    duration:
                      type: number
                      description: >-
                        Insertion playback duration which is accurate to
                        millisecond and cannot be longer than 24 hours.
                    backgroundColor:
                      type: string
                      description: >-
                        Text page background color, 16-number color value,
                        transparent by default, #00000000 denotes transparent
                        color.
                    timingTime:
                      type: string
                      description: 'Scheduled insertion time, example: 12:00:00.'
                  x-apifox-orders:
                    - spotsType
                    - normalProgramStatus
                    - duration
                    - timingTime
                    - backgroundColor
                  description: Basic emergency insertion properties
                  required:
                    - spotsType
                    - duration
                    - normalProgramStatus
                page:
                  type: array
                  items:
                    type: object
                    properties:
                      name:
                        type: string
                        description: Page name
                      widgets:
                        type: array
                        items:
                          type: object
                          properties:
                            name:
                              type: string
                              description: >-
                                Widget name. This is used for querying logs. If
                                this is empty, it will be difficult to
                                distinguish when you query logs.
                            type:
                              type: string
                              description: >-
                                Widget type, PICTURE - Image, VIDEO-Video,
                                ARCH_TEXT- Text.
                            md5:
                              type: string
                              description: >-
                                The image and video are required. The content is
                                the md5 value of the image or video.
                            size:
                              type: number
                              description: >-
                                Image size or video size (byte) which is
                                required.
                            duration:
                              type: number
                              description: >-
                                Playback duration of a widget which is accurate
                                to millisecond.
                            url:
                              type: string
                              description: >-
                                Image or Video URL, You are required to verify
                                the URL validity.
                            zIndex:
                              type: integer
                              description: >-
                                Layer order of a widget. The greater the number,
                                the upper the layer. It defaults to 0.
                            layout:
                              type: object
                              properties:
                                x:
                                  type: string
                                  description: >-
                                    Position of a widget relative to the left
                                    side of the page, example: 10%.
                                'y':
                                  type: string
                                  description: >-
                                    Position of a widget relative to the top of
                                    the page, example: 10%.
                                width:
                                  type: string
                                  description: >-
                                    Widget width relative to the page, example:
                                    100%.
                                height:
                                  type: string
                                  description: >-
                                    Widget height relative to the page, example:
                                    100%.
                              x-apifox-orders:
                                - x
                                - 'y'
                                - width
                                - height
                              description: Widget position on a page
                              required:
                                - x
                                - height
                                - width
                                - 'y'
                            inAnimation:
                              type: object
                              properties:
                                type:
                                  type: string
                                  description: >-
                                    Effect type NONE-No effect RANDOM-Random For
                                    others, see Entrance Effects.
                                duration:
                                  type: number
                                  description: Effect duration (millisecond)
                              x-apifox-orders:
                                - type
                                - duration
                              required:
                                - type
                                - duration
                              description: >-
                                Entrance effect of a widget. No effect by
                                default.
                          x-apifox-orders:
                            - name
                            - type
                            - md5
                            - size
                            - duration
                            - url
                            - zIndex
                            - layout
                            - inAnimation
                          required:
                            - layout
                            - url
                            - duration
                            - size
                            - md5
                            - type
                          description: Widgets on a page
                        description: Widgets on a page
                    x-apifox-refs: {}
                    x-apifox-orders:
                      - name
                      - widgets
                    required:
                      - name
                      - widgets
                    description: Solution content to be played
                  description: Solution content to be played
              x-apifox-orders:
                - playerIds
                - attribute
                - page
              required:
                - playerIds
                - page
                - attribute
            example:
              playerIds:
                - 52a66b84e5e241908a5dd317e82556d8
              attribute:
                duration: 20000
                normalProgramStatus: PAUSE
                spotsType: IMMEDIATELY
              page:
                name: a-immediately
                widgets:
                  - duration: 10000
                    inAnimation:
                      duration: 1000
                      type: NONE
                    layout:
                      height: 20%
                      width: 100%
                      x: 0%
                      'y': 60%
                    md5: 8330dcaa949ceeafa54a66e8ad623300
                    size: 25943
                    type: PICTURE
                    url: >-
                      http://vnnox-public.oss-cn-qingdao.aliyuncs.com/myf/test.jpg
                    zIndex: 1
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are successfully sent
                  fail:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are not sent successfully
                required:
                  - success
                  - fail
                x-apifox-orders:
                  - success
                  - fail
              example:
                success:
                  - 6226495f8a030b075e2f6757236620e2
                fail: []
          headers: {}
          x-apifox-name: OK
      security: []
      x-apifox-folder: VNNOX/Solutions/Emergency Insertion
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-180502122-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```

# Single-Page Emergency Insertion Solutions

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/emergency-program/page:
    post:
      summary: Single-Page Emergency Insertion Solutions
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for making emergency insertion solutions and
        publishing them to players.

        2. Sub-accounts can only manage data within their specific workgroup and
        its sub-workgroups.

        3. Advanced interface.

        :::
      tags:
        - VNNOX/Solutions/Emergency Insertion
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                    description: player IDs
                  description: >-
                    A collection of player IDs to be handled. At most 100 player
                    IDs can be handled simultaneously.
                attribute:
                  type: object
                  properties:
                    spotsType:
                      type: string
                      description: >-
                        Emergency insertion type, IMMEDIATELY: Immediate
                        insertion, TIMING: Scheduled insertion.
                    normalProgramStatus:
                      type: string
                      description: >-
                        Playback status of a common solution,
                        NORMAL-Normal，PAUSE -Paused.
                    duration:
                      type: number
                      description: >-
                        Insertion playback duration which is accurate to
                        millisecond and cannot be longer than 24 hours.
                    backgroundColor:
                      type: string
                      description: >-
                        Text page background color, 16-number color value,
                        transparent by default, #00000000 denotes transparent
                        color.
                    timingTime:
                      type: string
                      description: 'Scheduled insertion time, example: 12:00:00.'
                  x-apifox-orders:
                    - spotsType
                    - normalProgramStatus
                    - duration
                    - timingTime
                    - backgroundColor
                  description: Basic emergency insertion properties
                  required:
                    - spotsType
                    - duration
                    - normalProgramStatus
                page:
                  type: array
                  items:
                    type: object
                    properties:
                      name:
                        type: string
                        description: Page name
                      widgets:
                        type: array
                        items:
                          type: object
                          properties:
                            name:
                              type: string
                              description: >-
                                Widget name. This is used for querying logs. If
                                this is empty, it will be difficult to
                                distinguish when you query logs.
                            type:
                              type: string
                              description: >-
                                Widget type, PICTURE - Image, VIDEO-Video,
                                ARCH_TEXT- Text.
                            md5:
                              type: string
                              description: >-
                                The image and video are required. The content is
                                the md5 value of the image or video.
                            size:
                              type: number
                              description: >-
                                Image size or video size (byte) which is
                                required.
                            duration:
                              type: number
                              description: >-
                                Playback duration of a widget which is accurate
                                to millisecond.
                            url:
                              type: string
                              description: >-
                                Image or Video URL, You are required to verify
                                the URL validity.
                            zIndex:
                              type: integer
                              description: >-
                                Layer order of a widget. The greater the number,
                                the upper the layer. It defaults to 0.
                            layout:
                              type: object
                              properties:
                                x:
                                  type: string
                                  description: >-
                                    Position of a widget relative to the left
                                    side of the page, example: 10%.
                                'y':
                                  type: string
                                  description: >-
                                    Position of a widget relative to the top of
                                    the page, example: 10%.
                                width:
                                  type: string
                                  description: >-
                                    Widget width relative to the page, example:
                                    100%.
                                height:
                                  type: string
                                  description: >-
                                    Widget height relative to the page, example:
                                    100%.
                              x-apifox-orders:
                                - x
                                - 'y'
                                - width
                                - height
                              description: Widget position on a page
                              required:
                                - x
                                - height
                                - width
                                - 'y'
                            inAnimation:
                              type: object
                              properties:
                                type:
                                  type: string
                                  description: >-
                                    Effect type NONE-No effect RANDOM-Random For
                                    others, see Entrance Effects.
                                duration:
                                  type: number
                                  description: Effect duration (millisecond)
                              x-apifox-orders:
                                - type
                                - duration
                              required:
                                - type
                                - duration
                              description: >-
                                Entrance effect of a widget. No effect by
                                default.
                          x-apifox-orders:
                            - name
                            - type
                            - md5
                            - size
                            - duration
                            - url
                            - zIndex
                            - layout
                            - inAnimation
                          required:
                            - layout
                            - url
                            - duration
                            - size
                            - md5
                            - type
                          description: Widgets on a page
                        description: Widgets on a page
                    x-apifox-refs: {}
                    x-apifox-orders:
                      - name
                      - widgets
                    required:
                      - name
                      - widgets
                    description: Solution content to be played
                  description: Solution content to be played
              x-apifox-orders:
                - playerIds
                - attribute
                - page
              required:
                - playerIds
                - page
                - attribute
            example:
              playerIds:
                - 52a66b84e5e241908a5dd317e82556d8
              attribute:
                duration: 20000
                normalProgramStatus: PAUSE
                spotsType: IMMEDIATELY
              page:
                name: a-immediately
                widgets:
                  - duration: 10000
                    inAnimation:
                      duration: 1000
                      type: NONE
                    layout:
                      height: 20%
                      width: 100%
                      x: 0%
                      'y': 60%
                    md5: 8330dcaa949ceeafa54a66e8ad623300
                    size: 25943
                    type: PICTURE
                    url: >-
                      http://vnnox-public.oss-cn-qingdao.aliyuncs.com/myf/test.jpg
                    zIndex: 1
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are successfully sent
                  fail:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are not sent successfully
                required:
                  - success
                  - fail
                x-apifox-orders:
                  - success
                  - fail
              example:
                success:
                  - 6226495f8a030b075e2f6757236620e2
                fail: []
          headers: {}
          x-apifox-name: OK
      security: []
      x-apifox-folder: VNNOX/Solutions/Emergency Insertion
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-180502122-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Canceling Emergency Insertion Solutions

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/emergency-program/cancel:
    post:
      summary: Canceling Emergency Insertion Solutions
      deprecated: false
      description: >-

        :::tip

        1. This interface can be used for canceling the emergency insertion
        solution being played.

        2. Sub-accounts can only manage data within their specific workgroup and
        its sub-workgroups.

        3. Advanced interface.

        :::
      tags:
        - VNNOX/Solutions/Emergency Insertion
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                    description: player IDs
                  description: >-
                    A collection of player IDs to be handled. At most 100 player
                    IDs can be handled simultaneously.
              x-apifox-orders:
                - playerIds
              required:
                - playerIds
            example:
              playerIds:
                - 553cbfe2ff4ad2e0d6bd89bb2c4e85e2
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are successfully sent.
                  fail:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are not sent successfully.
                required:
                  - success
                  - fail
                x-apifox-orders:
                  - success
                  - fail
              example:
                success:
                  - 553cbfe2ff4ad2e0d6bd89bb2c4e85e2
                fail: []
          headers: {}
          x-apifox-name: OK
      security: []
      x-apifox-folder: VNNOX/Solutions/Emergency Insertion
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-180502123-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Hand-drawn clock widget


:::highlight purple 💡
This page describes the dedicated data structure and provide an example for API
[Common Solutions](https://developer-en.vnnox.com/api-180502121.md) when *widget type* = **DRAWN_DIGITAL_CLOCK**
:::
<Columns>
  <Column>
    <DataSchema id="162442578" />
  </Column>
  <Column>
```json
    {
      "playerIds": [
        "553cbfe2ff4ad2e0d6bd89bb2c4e85e2"
      ],
      "schedule": {
        "startDate": "2020-04-11",
        "endDate": "2060-05-12",
        "plans": [
          {
            "weekDays": [
              1,
              2,
              3,
              4,
              5
            ],
            "startTime": "00:00:00",
            "endTime": "22:00:00"
          },
          {
            "weekDays": [
              0,
              6
            ],
            "startTime": "00:33:00",
            "endTime": "22:00:00"
          }
        ]
      },
      "pages": [
        {
          "name": "a-page",
          "widgets": [
            {
              "zIndex": 1,
              "type": "DRAWN_DIGITAL_CLOCK",
              "zone": "Asia/Shanghai",
              "gmt": "GMT+08:00",
              "regular": "$MM/$dd/$yyyy\n$E\n$N $hh:$mm:$ss",
              "weekTemplates": [
                "Sun",
                "Mon",
                "Tue",
                "Wed",
                "Thu",
                "Fri",
                "Sat"
              ],
              "suffixTemplates": [
                "AM",
                "PM"
              ],
              "textColor": "#ff0000",
              "fontSize": 14,
              "fontFamily": [
                "Times",
                "Georia",
                "New York"
              ],
              "fontStyle": "Bold",
              "fontIsUnderline": false,
              "backgroundColor": "#00ff00",
              "shadowEnable": false,
              "shadowRadius": 10,
              "shadowDx": 2,
              "shadowDy": 2,
              "shadowColor": "#00ff00",
              "layout": {
                "x": "0%",
                "y": "0%",
                "width": "30%",
                "height": "30%"
              }
            }
          ]
        }
      ]
    }
    ```
  </Column>
</Columns>



# Text component


<DataSchema id="220230730" />


# RSS component


<DataSchema id="220235090" />


# WEATHER - Simple Weather Component


<DataSchema id="220236771" />


# ANALOG_WEATHER - Basic Weather Component


<DataSchema id="220244495" />


# ANALOG_WEATHER - Basic Weather Component


<DataSchema id="220247740" />


# Weather widget

:::highlight purple 💡
This page describes the dedicated data structure and provide an example for API
[Common Solutions](https://developer-en.vnnox.com/api-180502121.md) when *widget type* = **WEATHER**
:::
<Columns>
  <Column>
    <DataSchema id="162442578" />
  </Column>
  <Column>
    ```json
{
  "playerIds": [
    "553cbfe2ff4ad2e0d6bd89bb2c4e85e2"
  ],
  "schedule": {
    "startDate": "2020-04-11",
    "endDate": "2060-05-12",
    "plans": [
      {
        "weekDays": [
          1,
          2,
          3,
          4,
          5
        ],
        "startTime": "00:00:00",
        "endTime": "22:00:00"
      },
      {
        "weekDays": [
          0,
          6
        ],
        "startTime": "00:33:00",
        "endTime": "22:00:00"
      }
    ]
  },
  "pages": [
    {
      "name": "a-page",
      "widgets": [
        {
          "zIndex": 1,
         "lang":"en",
          "type": "WEATHER",
          "address": "Beijing Dongcheng District Chang'an Street",
          "latitude": 39.9087,
          "longitude": 116.3974,
          "width": 147,
          "height": 140,
          "refreshPeriod": 600000,
          "fontSize": 14,
          "bold": false,
          "italic": false,
          "underline": false,
          "color": "#00FFD4",
          "tempUnit": 0,
          "unitSymbol": 1,
          "weatherEnable": true,
          "tempEnable": true,
          "windEnable": true,
          "humidEnable": true,
          "currentTempEnable": true,
          "isShowInOneLine": false,
          "duration": 10000,
          "layout": {
            "x": "40%",
            "y": "40%",
            "width": "50%",
            "height": "50%"
          }
        }
      ]
    }
  ]
}
    ```
  </Column>
</Columns>


# Environmental Monitoring widget

:::highlight purple 💡
This page describes the dedicated data structure and provide an example for API
[Common Solutions](https://developer-en.vnnox.com/api-180502121.md) when *widget type* = **RT_MEDIA**
:::
<Columns>
  <Column>
    <DataSchema id="162442578" />
  </Column>
  <Column>
```json
{
    "playerIds": [
        "533d7c3151392500a57ba8b0d06436b4"
    ],
    "schedule": {
        "startDate": "2020-04-11",
        "endDate": "2060-05-12",
        "plans": [
            {
                "weekDays": [
                    1,
                    2,
                    3,
                    4,
                    5
                ],
                "startTime": "00:00:00",
                "endTime": "22:00:00"
            },
            {
                "weekDays": [
                    0,
                    6
                ],
                "startTime": "00:33:00",
                "endTime": "22:00:00"
            }
        ]
    },
    "pages": [
        {
            "name": "a-page",
            "widgets": [
                {
                    "zIndex": 1,
                    "type": "RT_MEDIA",
                    "fontFamily": "SimSun",
                    "labelFontSize": 18,
                    "valueFontSize": 18,
                    "unitFontSize": 18,
                    "bold": true,
                    "italic": true,
                    "underline": true,
                    "textColor": "#F90840",
                    "refreshPeriod": 600000,
                    "style": 1,
                    "playMode": "STATIC",
                    "seedByPixelEnable": false,
                    "speedGear": 3,
                    "speedPixel": 160,
                    "customLabel": {
                        "NOI": {
                            "name": "NAIs",
                            "enable": true,
                            "unit": "ions/cm³"
                        },
                        "coII": {
                            "name": "CO2",
                            "enable": true,
                            "unit": "ppm"
                        },
                        "UVR": {
                            "name": "UV radiation",
                            "enable": true,
                            "unit": "nm"
                        },
                        "airHumidity": {
                            "name": "Air humidity",
                            "enable": true,
                            "unit": "%RH"
                        },
                        "airPressure": {
                            "name": "Air pressure",
                            "enable": true,
                            "type": 0,
                            "unit": "bar"
                        },
                        "ambiantLight": {
                            "name": "Ambient Light",
                            "enable": true,
                            "unit": "Lux"
                        },
                        "noise": {
                            "name": "Noise",
                            "enable": true,
                            "unit": "db"
                        },
                        "pmC": {
                            "name": "PM100",
                            "enable": true,
                            "unit": "μg/m³"
                        },
                        "pmIIV": {
                            "name": "PM2.5",
                            "enable": true,
                            "unit": "μg/m³"
                        },
                        "pmX": {
                            "name": "PM10",
                            "enable": true,
                            "unit": "μg/m³"
                        },
                        "rainfall": {
                            "name": "Rainfall",
                            "enable": true,
                            "unit": "mm"
                        },
                        "snowfall": {
                            "name": "Snowfall",
                            "enable": true,
                            "unit": "mm"
                        },
                        "soilMoisture": {
                            "name": "Soil moisture",
                            "enable": true,
                            "unit": "rh"
                        },
                        "soilPH": {
                            "name": "Soil pH",
                            "enable": true,
                            "unit": "ph"
                        },
                        "soilTemperature": {
                            "name": "Soil temperature",
                            "enable": true,
                            "type": 0,
                            "unit": "℃"
                        },
                        "sunshineDuration": {
                            "name": "Sunshine duration",
                            "enable": true,
                            "unit": "W/m²"
                        },
                        "temperature": {
                            "name": "Temperature",
                            "enable": true,
                            "type": 0,
                            "unit": "C°",
                            "tempCompensate": 0
                        },
                        "windDirection": {
                            "name": "Wind direction",
                            "enable": true,
                            "templates": [
                                "North Wind",
                                "Northeast Wind",
                                "East Wind",
                                "Southeast Wind",
                                "South Wind",
                                "Southwest Wind",
                                "West Wind",
                                "Northwest Wind
"
                            ]
                        },
                        "windSpeed": {
                            "name": "Wind Speed",
                            "enable": true,
                            "type": 0,
                            "unit": "km/h"
                        }
                    },
                    "duration": 10000,
                    "layout": {
                        "x": "0%",
                        "y": "0%",
                        "width": "50%",
                        "height": "50%"
                    }
                }
            ]
        }
    ]
}
```
  </Column>
</Columns>



# Common Solutions

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/program/normal:
    post:
      summary: Common Solutions
      deprecated: false
      description: >-
        :::tip

        1. This interface is used for making solutions and publishing them to
        players.

        2. Taurus later than V2.0.0 are supported.

        3. Sub-accounts can only manage data within their specific workgroup and
        its sub-workgroups.

        4. Advanced interface.

        :::



        :::highlight purple 💡

        The **widgets** field supports polymorphic structures - its schema
        varies depending on the type value. Standard widgets are already
        included, for extended types, see:

        - [Hand-drawn clock
        widget](https://developer-en.vnnox.com/doc-6506467.md)

        - [Weather widget](https://developer-en.vnnox.com/doc-6506699.md)

        - [Environmental Monitoring
        widget](https://developer-en.vnnox.com/doc-6507057.md)

        :::
      tags:
        - VNNOX/Solutions
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                    description: player IDs
                  description: >-
                    A collection of player IDs to be handled. At most 100 player
                    IDs can be handled simultaneously.
                schedule:
                  type: object
                  properties:
                    startDate:
                      type: string
                      description: 'Playback start date, example: 2020-04-12.'
                      x-apifox-mock: '2020-04-12'
                    endDate:
                      type: string
                      description: 'Playback end date, example: 2020-12-22.'
                      x-apifox-mock: '2020-12-22'
                    plans:
                      type: array
                      items:
                        type: object
                        properties:
                          weekDays:
                            type: array
                            items:
                              type: integer
                              description: Playback days of the week
                            description: >-
                              Playback days of the week, 0-Sunday, 1-Monday,
                              2-Tuesday, 3-Wednesday, 4-Thursday, 5-Friday,
                              6-Saturday.
                          startTime:
                            type: string
                            description: 'Start time of the day, example: 08:00.'
                            x-apifox-mock: '08:00'
                          endTime:
                            type: string
                            description: End time of the day, example:18:00.
                            x-apifox-mock: '18:00'
                        x-apifox-orders:
                          - weekDays
                          - startTime
                          - endTime
                        required:
                          - weekDays
                          - endTime
                          - startTime
                        description: Specific playback plan
                      description: Specific playback plan
                  x-apifox-orders:
                    - startDate
                    - endDate
                    - plans
                  description: >-
                    Playback schedule. If this is empty, the playback will be
                    repeated 24 hours.
                  required:
                    - plans
                    - startDate
                    - endDate
                noticeUrl:
                  type: string
                  description: >-
                    The Program Download Progress Notification Interface is used
                    to send the download progress of a program to the client via
                    this interface. The response time of the interface must not
                    exceed 3 seconds.


                    Callback Example

                    {

                    "playerId": "553cbfe2ff4ad2e0d6bd89bb2c4e85e2",

                    "precess": 0.5,

                    "status": 0

                    }


                    precess: Program download progress, range: 0-1

                    status: Status, 0 - Normal; 1001 - Media download exception
                    (usually due to network issues preventing download), file
                    MD5 verification failed; 1002 - Media exceeds specifications
                    and cannot be played

                    errmsg: When status is not 0, this field contains the
                    description of the exception information
                pages:
                  type: array
                  items:
                    type: object
                    properties:
                      name:
                        type: string
                        description: Page name
                      schedules:
                        type: array
                        items:
                          type: object
                          properties:
                            startDate:
                              type: string
                              description: 'Playback start date, example: 2020-04-12.'
                              x-apifox-mock: '2020-04-12'
                            endDate:
                              type: string
                              description: 'Playback end date, example: 2020-12-22.'
                              x-apifox-mock: '2020-12-22'
                            plans:
                              type: array
                              items:
                                type: object
                                properties:
                                  weekDays:
                                    type: string
                                    description: >-
                                      Playback days of the week, 0-Sunday,
                                      1-Monday, 2-Tuesday, 3-Wednesday,
                                      4-Thursday, 5-Friday, 6-Saturday.
                                  startTime:
                                    type: string
                                    description: 'Start time of the day, example: 08:00.'
                                    x-apifox-mock: '08:00'
                                  endTime:
                                    type: string
                                    description: End time of the day, example:18:00.
                                    x-apifox-mock: '18:00'
                                x-apifox-orders:
                                  - weekDays
                                  - startTime
                                  - endTime
                                required:
                                  - weekDays
                                  - endTime
                                  - startTime
                                description: Specific playback plan
                              description: Specific playback plan
                          x-apifox-orders:
                            - startDate
                            - endDate
                            - plans
                          required:
                            - startDate
                            - plans
                            - endDate
                          description: Playback Schedule
                        description: >-
                          It denotes the schedule list for this page. If it is
                          empty, the page will be looped continuously for 24
                          hours. However, if both the program and page schedules
                          are set, the page will play only during the times that
                          overlap between the two schedules.
                      widgets:
                        type: array
                        items:
                          anyOf:
                            - description: 'Widget type: PICTURE | GIF | VIDEO'
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: >-
                                    Widget name. This is used for querying logs.
                                    If this is empty, it will be difficult to
                                    distinguish when you query logs.
                                type:
                                  type: string
                                  description: Widget type. PICTURE | GIF | VIDEO.
                                md5:
                                  type: string
                                  description: >-
                                    MD5 value of the image and video centent, it
                                    must be lowercase.
                                size:
                                  type: number
                                  description: Image or video size (byte)
                                duration:
                                  type: number
                                  description: >-
                                    Playback duration of a widget which is
                                    accurate to millisecond.
                                url:
                                  type: string
                                  description: >-
                                    Image\GIF\Video\RSS\Web page\Streaming media
                                    URL, You are required to verify the URL
                                    validity.
                                zIndex:
                                  type: integer
                                  description: >-
                                    Layer order of a widget. The greater the
                                    number, the upper the layer. It defaults to
                                    0.
                                layout:
                                  type: object
                                  properties:
                                    x:
                                      type: string
                                      description: >-
                                        Position of a widget relative to the
                                        left side of the page, example: 10%.
                                    'y':
                                      type: string
                                      description: >-
                                        Position of a widget relative to the top
                                        of the page, example: 10%.
                                    width:
                                      type: string
                                      description: >-
                                        Widget width relative to the page,
                                        example: 100%.
                                    height:
                                      type: string
                                      description: >-
                                        Widget height relative to the page,
                                        example: 100%.
                                  x-apifox-orders:
                                    - x
                                    - 'y'
                                    - width
                                    - height
                                  description: Widget position on a page
                                  required:
                                    - x
                                    - height
                                    - width
                                    - 'y'
                                inAnimation:
                                  type: object
                                  properties:
                                    type:
                                      type: string
                                      description: >-
                                        Effect type NONE-No effect RANDOM-Random
                                        For others
                                    duration:
                                      type: number
                                      description: Effect duration (millisecond)
                                  x-apifox-orders:
                                    - type
                                    - duration
                                  required:
                                    - type
                                    - duration
                                  description: >-
                                    Entrance effect of a widget. No effect by
                                    default.
                              x-apifox-refs: {}
                              x-apifox-orders:
                                - zIndex
                                - name
                                - type
                                - md5
                                - size
                                - duration
                                - url
                                - layout
                                - inAnimation
                              required:
                                - layout
                                - url
                                - duration
                                - size
                                - md5
                                - type
                            - type: object
                              properties:
                                name:
                                  type: string
                                  description: >-
                                    Widget name. This is used for querying logs.
                                    If this is empty, it will be difficult to
                                    distinguish when you query logs.
                                type:
                                  type: string
                                  description: 'Widget type: WEATHER. '
                                duration:
                                  type: number
                                  description: >-
                                    Playback duration of a widget which is
                                    accurate to millisecond.
                                zIndex:
                                  type: integer
                                  description: >-
                                    Layer order of a widget. The greater the
                                    number, the upper the layer. It defaults to
                                    0.
                                layout:
                                  type: object
                                  properties:
                                    x:
                                      type: string
                                      description: >-
                                        Position of a widget relative to the
                                        left side of the page, example: 10%.
                                    'y':
                                      type: string
                                      description: >-
                                        Position of a widget relative to the top
                                        of the page, example: 10%.
                                    width:
                                      type: string
                                      description: >-
                                        Widget width relative to the page,
                                        example: 100%.
                                    height:
                                      type: string
                                      description: >-
                                        Widget height relative to the page,
                                        example: 100%.
                                  x-apifox-orders:
                                    - x
                                    - 'y'
                                    - width
                                    - height
                                  description: Widget position on a page
                                  required:
                                    - x
                                    - height
                                    - width
                                    - 'y'
                                inAnimation:
                                  type: object
                                  properties:
                                    type:
                                      type: string
                                      description: >-
                                        Effect type NONE-No effect RANDOM-Random
                                        For others
                                    duration:
                                      type: number
                                      description: Effect duration (millisecond)
                                  x-apifox-orders:
                                    - type
                                    - duration
                                  required:
                                    - type
                                    - duration
                                  description: >-
                                    Entrance effect of a widget. No effect by
                                    default.
                                address:
                                  type: string
                                latitude:
                                  type: number
                                longitude:
                                  type: number
                                width:
                                  type: number
                                  description: >-
                                    The relative width of the weather widget
                                    component; The effect of the display is
                                    related to the layout parameter, if the
                                    screen display of the created  widget is
                                    incomplete, please increase this value
                                    appropriately.
                                height:
                                  type: number
                                  description: >-
                                    The relative height of the weather widget
                                    component; The effect of the display is
                                    related to the layout parameter, if the
                                    screen display of the created  widget is
                                    incomplete, please increase this value
                                    appropriately.
                                refreshPeriod:
                                  type: integer
                                  description: Refresh cycle in milliseconds
                                fontSize:
                                  type: integer
                                italic:
                                  type: boolean
                                underline:
                                  type: boolean
                                color:
                                  type: string
                                  description: 'Font color, format example: ''#00FFD4'' (Cyan)'
                                tempUnit:
                                  type: integer
                                  description: >-
                                    Temperature unit type (0: Celsius | 1:
                                    Fahrenheit)
                                unitSymbol:
                                  type: string
                                  description: >-
                                    Unit display type:  0=° (generic) or 1=℃/℉
                                    (specific); when using Fahrenheit
                                    (tempUnit=1), only 1 is allowed.
                                weatherEnable:
                                  type: boolean
                                  description: weather element toggle
                                tempEnable:
                                  type: boolean
                                  description: temperature element toggle
                                windEnable:
                                  type: boolean
                                  description: wind element toggle
                                humidEnable:
                                  type: boolean
                                  description: humidity element toggle
                                currentTempEnable:
                                  type: boolean
                                  description: real-time temperature element toggle
                                isShowInOneLine:
                                  type: boolean
                                  description: single line mode toggle
                                lang:
                                  type: string
                                  description: >-
                                    Multilingual 
                                    zh-Chinese,en-English,jp-Japanese,es-Spanish,fr-French,pt-Portuguese,it-Italian
                              x-apifox-refs: {}
                              x-apifox-orders:
                                - zIndex
                                - name
                                - type
                                - duration
                                - layout
                                - inAnimation
                                - address
                                - latitude
                                - longitude
                                - width
                                - height
                                - refreshPeriod
                                - fontSize
                                - italic
                                - underline
                                - color
                                - tempUnit
                                - unitSymbol
                                - weatherEnable
                                - tempEnable
                                - windEnable
                                - humidEnable
                                - currentTempEnable
                                - isShowInOneLine
                                - lang
                              required:
                                - layout
                                - duration
                                - type
                                - address
                                - weatherEnable
                                - unitSymbol
                                - tempUnit
                                - color
                                - underline
                                - italic
                                - fontSize
                                - refreshPeriod
                                - height
                                - width
                                - longitude
                                - latitude
                                - humidEnable
                                - windEnable
                                - tempEnable
                                - isShowInOneLine
                                - currentTempEnable
                                - lang
                              description: 'Widget type: WEATHER'
                            - type: object
                              properties:
                                zIndex:
                                  type: integer
                                  description: >-
                                    Layer order of a widget. The greater the
                                    number, the upper the layer. It defaults to
                                    0.
                                type:
                                  type: string
                                  description: >-
                                    Widget type: RT_MEDIA (Enviromental
                                    Monitoring widget)
                                fontFamily:
                                  type: string
                                  description: |-
                                    Text Font (Default: Arial)
                                    Supported Player Fonts:
                                    SimSun
                                    Microsoft YaHei
                                    KaiTi
                                    Arial
                                    Wingdings 2
                                    Calibri
                                labelFontSize:
                                  type: integer
                                  description: Label Font Size. Range:9~256
                                valueFontSize:
                                  type: integer
                                  description: Data Font Size. Range:9~256
                                unitFontSize:
                                  type: integer
                                  description: Unit Font Size. Range:9~256
                                bold:
                                  type: boolean
                                  description: 'Bold (Default: false)'
                                italic:
                                  type: boolean
                                  description: 'Italic (Default: false)'
                                underline:
                                  type: boolean
                                  description: 'Underline (Default: false)'
                                textColor:
                                  type: string
                                  description: 'Font Color: #00FFD4'
                                refreshPeriod:
                                  type: integer
                                  description: 'Data Refresh Interval (Unit: ms)'
                                style:
                                  type: integer
                                  description: >-
                                    Playback Style (Default: 1, Options:
                                    1-Style1, 2-Style2, 3-Style3, 4-Style4)
                                playMode:
                                  type: string
                                  description: >-
                                    Play Mode，(Default: STATIC, Options: STATI,
                                    SCROLL)
                                seedByPixelEnable:
                                  type: boolean
                                  description: >-
                                    Enable Pixel Scrolling (true: Pixel, false:
                                    Gear)
                                speedGear:
                                  type: integer
                                  description: >-
                                    Gear Level (Effective when playMode=Scroll
                                    AND seedByPixelEnable=false, Range: 1~10)
                                speedPixel:
                                  type: integer
                                  description: >-
                                    Pixel Value (Effective when playMode=Scroll
                                    AND seedByPixelEnable=true, Range: 10~500px)
                                customLabel:
                                  type: object
                                  properties:
                                    NOI:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: Display name
                                        enable:
                                          type: boolean
                                          description: Enable or not，false-not，true-enable
                                        unit:
                                          type: string
                                          description: >-
                                            unit，Recommended Value：ions/cm³，Supports
                                            customization
                                      required:
                                        - name
                                        - enable
                                        - unit
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - unit
                                      description: NAI
                                    UVR:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: Display name
                                        enable:
                                          type: boolean
                                          description: Enable or not，false-not，true-enable
                                        unit:
                                          type: string
                                          description: >-
                                            unit，Recommended Value：nm，Supports
                                            customization
                                      required:
                                        - name
                                        - enable
                                        - unit
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - unit
                                      description: UV Radiation
                                    airHumidity:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: Display name
                                        enable:
                                          type: boolean
                                          description: Enable or not，false-not，true-enable
                                        unit:
                                          type: string
                                          description: >-
                                            Unit，Recommended Value：%RH，Supports
                                            customization
                                      required:
                                        - name
                                        - enable
                                        - unit
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - unit
                                      description: airHumidity
                                    airPressure:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: 气压显示名称
                                        enable:
                                          type: boolean
                                          description: 是否显示，false-不显示，true-显示
                                        type:
                                          type: integer
                                          description: >-
                                            气压数据类型，枚举值：0-KPa 1-bar 2-atm 3-mmHg
                                            4-Torr 5-kgf/cm2，6-hpa，默认：0
                                        unit:
                                          type: string
                                          description: 温度单位，请对应数据类型传参，例如，type=0，unitName=KPa
                                      required:
                                        - name
                                        - enable
                                        - type
                                        - unit
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - type
                                        - unit
                                      description: airPressure
                                    ambiantLight:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: Display name
                                        enable:
                                          type: boolean
                                          description: Enable or not，false-not，true-enable
                                        unit:
                                          type: string
                                          description: >-
                                            Unit，Recommended Value：Lux，Supports
                                            customization
                                      required:
                                        - name
                                        - enable
                                        - unit
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - unit
                                      description: 'Illuminance '
                                    coII:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: Display name
                                        enable:
                                          type: boolean
                                          description: Enable or not，false-not，true-enable
                                        unit:
                                          type: string
                                          description: >-
                                            Unit，Recommended Value：ppm，Supports
                                            customization
                                      required:
                                        - name
                                        - enable
                                        - unit
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - unit
                                      description: Carbon Dioxide
                                    noise:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: Display name
                                        enable:
                                          type: boolean
                                          description: Enable or not，false-not，true-enable
                                        unit:
                                          type: string
                                          description: >-
                                            Unit，Recommended Value：db，Supports
                                            customization
                                      required:
                                        - name
                                        - enable
                                        - unit
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - unit
                                      description: noise
                                    pmC:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: Display name
                                        enable:
                                          type: boolean
                                          description: Enable or not，false-not，true-enable
                                        unit:
                                          type: string
                                          description: >-
                                            Unit，Recommended Value：μg/m3，Supports
                                            customization
                                      required:
                                        - name
                                        - enable
                                        - unit
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - unit
                                      description: PM100
                                    pmIIV:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: Display name
                                        enable:
                                          type: boolean
                                          description: Enable or not，false-not，true-enable
                                        unit:
                                          type: string
                                          description: >-
                                            Unit，Recommended Value：μg/m3，Supports
                                            customization
                                      required:
                                        - name
                                        - enable
                                        - unit
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - unit
                                      description: PM2.5
                                    pmX:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: Display name
                                        enable:
                                          type: boolean
                                          description: Enable or not，false-not，true-enable
                                        unit:
                                          type: string
                                          description: >-
                                            Unit，Recommended Value：μg/m3，Supports
                                            customization
                                      required:
                                        - name
                                        - enable
                                        - unit
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - unit
                                      description: PM10
                                    rainfall:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: Display name
                                        enable:
                                          type: boolean
                                          description: Enable or not，false-not，true-enable
                                        unit:
                                          type: string
                                          description: >-
                                            Unit，Recommended Value：mm，Supports
                                            customization
                                      required:
                                        - name
                                        - enable
                                        - unit
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - unit
                                      description: Rainfall
                                    snowfall:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: Display name
                                        enable:
                                          type: boolean
                                          description: Enable or not，false-not，true-enable
                                        unit:
                                          type: string
                                          description: >-
                                            Unit，Recommended Value：mm，Supports
                                            customization
                                      required:
                                        - name
                                        - enable
                                        - unit
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - unit
                                      description: Snowfall
                                    soilMoisture:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: Display name
                                        enable:
                                          type: boolean
                                          description: Enable or not，false-not，true-enable
                                        unit:
                                          type: string
                                          description: >-
                                            Unit，Recommended Value：RH，Supports
                                            customization
                                      required:
                                        - name
                                        - enable
                                        - unit
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - unit
                                      description: Soil Moisture
                                    soilPH:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: Display name
                                        enable:
                                          type: boolean
                                          description: Enable or not，false-not，true-enable
                                        unit:
                                          type: string
                                          description: >-
                                            Unit，Recommended Value：pH，Supports
                                            customization
                                      required:
                                        - name
                                        - enable
                                        - unit
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - unit
                                      description: Soil pH
                                    soilTemperature:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: Display name
                                        enable:
                                          type: boolean
                                          description: Enable or not，false-not，true-enable
                                        type:
                                          type: integer
                                          description: >-
                                            Soil Temperature Data Type, 0: Celsius
                                            (°C) (default)  1: Fahrenheit (°F)
                                        unit:
                                          type: string
                                          description: >-
                                            Unit，Please pass parameters
                                            corresponding to the data type，For
                                            example, type=0, unit=℃
                                      required:
                                        - name
                                        - enable
                                        - type
                                        - unit
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - type
                                        - unit
                                      description: Soil Temperature
                                    sunshineDuration:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: Display name
                                        enable:
                                          type: boolean
                                          description: Enable or not，false-not，true-enable
                                        unit:
                                          type: string
                                          description: >-
                                            Unit，Recommended Value：W/m2，Supports
                                            customization
                                      required:
                                        - name
                                        - enable
                                        - unit
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - unit
                                      description: Sunshine Duration
                                    temperature:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: Display name
                                        enable:
                                          type: boolean
                                          description: Enable or not，false-not，true-enable
                                        type:
                                          type: integer
                                          description: >-
                                            Temperature Data Type, 0: Celsius (°C)
                                            (default)  1: Fahrenheit (°F)
                                        unit:
                                          type: string
                                          description: >-
                                            Unit，Please pass parameters
                                            corresponding to the data type，For
                                            example, type=0, unit=℃
                                        tempCompensate:
                                          type: integer
                                          description: Compensate，Default：0，Range：-50~50
                                      required:
                                        - name
                                        - enable
                                        - type
                                        - unit
                                        - tempCompensate
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - type
                                        - unit
                                        - tempCompensate
                                      description: temperature
                                    windDirection:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: Display name
                                        enable:
                                          type: boolean
                                          description: Enable or not，false-not，true-enable
                                        templates:
                                          type: array
                                          items:
                                            type: string
                                          description: >-
                                            this field is mandatory when wind
                                            direction data is available. Values must
                                            strictly follow the predefined order
                                            below:
                                            North,Northeast,East,Southeast,South,Southwest,West,Northwest
                                      required:
                                        - name
                                        - enable
                                        - templates
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - templates
                                      description: Wind Direction
                                    windSpeed:
                                      type: object
                                      properties:
                                        name:
                                          type: string
                                          description: Display name
                                        enable:
                                          type: boolean
                                          description: Enable or not，false-not，true-enable
                                        type:
                                          type: integer
                                          description: >-
                                            Wind Speed Data Type，0: Kilometers Per
                                            Hour, 1: Meters Per Second, 2: level
                                        unit:
                                          type: string
                                          description: >-
                                            Unit，Please pass parameters
                                            corresponding to the data type，For
                                            example, type=0, unit=km/h
                                      required:
                                        - name
                                        - enable
                                        - type
                                        - unit
                                      x-apifox-orders:
                                        - name
                                        - enable
                                        - type
                                        - unit
                                      description: Wind Speed
                                  required:
                                    - NOI
                                    - UVR
                                    - airHumidity
                                    - airPressure
                                    - ambiantLight
                                    - coII
                                    - noise
                                    - pmC
                                    - pmIIV
                                    - pmX
                                    - rainfall
                                    - snowfall
                                    - soilMoisture
                                    - soilPH
                                    - soilTemperature
                                    - sunshineDuration
                                    - temperature
                                    - windDirection
                                    - windSpeed
                                  x-apifox-orders:
                                    - NOI
                                    - UVR
                                    - airHumidity
                                    - airPressure
                                    - ambiantLight
                                    - coII
                                    - noise
                                    - pmC
                                    - pmIIV
                                    - pmX
                                    - rainfall
                                    - snowfall
                                    - soilMoisture
                                    - soilPH
                                    - soilTemperature
                                    - sunshineDuration
                                    - temperature
                                    - windDirection
                                    - windSpeed
                                  description: Item Attribute Structure
                                duration:
                                  type: integer
                                  description: Effect duration (millisecond)
                                layout:
                                  type: object
                                  properties:
                                    x:
                                      type: string
                                      description: >-
                                        Position of a widget relative to the
                                        left side of the page, example: 10%.
                                    'y':
                                      type: string
                                      description: >-
                                        Position of a widget relative to the top
                                        of the page, example: 10%.
                                    width:
                                      type: string
                                      description: >-
                                        Widget width relative to the page,
                                        example: 100%.
                                    height:
                                      type: string
                                      description: >-
                                        Widget height relative to the page,
                                        example: 100%.
                                  required:
                                    - x
                                    - 'y'
                                    - width
                                    - height
                                  x-apifox-orders:
                                    - x
                                    - 'y'
                                    - width
                                    - height
                                  description: Widget position on a page
                              x-apifox-refs: {}
                              x-apifox-orders:
                                - zIndex
                                - type
                                - fontFamily
                                - labelFontSize
                                - valueFontSize
                                - unitFontSize
                                - bold
                                - italic
                                - underline
                                - textColor
                                - refreshPeriod
                                - style
                                - playMode
                                - seedByPixelEnable
                                - speedGear
                                - speedPixel
                                - customLabel
                                - duration
                                - layout
                              required:
                                - zIndex
                                - type
                                - fontFamily
                                - labelFontSize
                                - valueFontSize
                                - unitFontSize
                                - bold
                                - italic
                                - underline
                                - textColor
                                - refreshPeriod
                                - style
                                - playMode
                                - seedByPixelEnable
                                - speedGear
                                - speedPixel
                                - customLabel
                                - duration
                                - layout
                              description: >-
                                Widget type: RT_MEDIA (Enviromental Monitoring
                                widget)
                            - type: object
                              properties:
                                type:
                                  type: string
                                  description: 'Widget type: DRAWN_DIGITAL_CLOCK'
                                zIndex:
                                  type: integer
                                  description: >-
                                    Component overlay order, the larger the
                                    number, the upper the layer, default 0
                                layout:
                                  type: object
                                  properties:
                                    x:
                                      type: string
                                      description: >-
                                        The component's position relative to the
                                        left side of the page, such as: 10%
                                    'y':
                                      type: string
                                      description: >-
                                        The component's position relative to the
                                        top of the page, such as: 10%
                                    width:
                                      type: string
                                      description: >-
                                        The width of the component relative to
                                        the page, such as: 100%
                                    height:
                                      type: string
                                      description: >-
                                        The height of the component relative to
                                        the page, such as: 100%
                                  x-apifox-orders:
                                    - x
                                    - 'y'
                                    - width
                                    - height
                                  description: The location of the component on the page
                                  required:
                                    - x
                                    - height
                                    - width
                                    - 'y'
                                zone:
                                  type: string
                                  description: >-
                                    IANA Time Zone Identifier, e.g. 
                                    "America/Los_Angeles"
                                gmt:
                                  type: string
                                  description: GMT Time Zone, e.g. "GMT-08:00"
                                regular:
                                  type: string
                                  description: >-
                                    The display rules of the digital clock and
                                    the placeholder are defined as follows: 

                                    \$dd: represents the day;

                                    \$MM: Represents the month;

                                    \$yyyy  Represents the year in 4 digits,
                                    while \$yy in 2 digits;

                                    \$E: A placeholder for the day of the week;

                                    \$HH: hour, in 24-hour format;

                                    \$hh: hour, in 12-hour format;

                                    \$mm: minutes;

                                    \$ss: seconds;

                                    \$N: morning or afternoon;

                                    \\n: Lines are wrapped;
                                weekTemplates:
                                  type: array
                                  items:
                                    type: string
                                  description: >-
                                    Display template for the week, seven data
                                    items, representing Monday to Sunday
                                    respectively
                                suffixTemplates:
                                  type: array
                                  items:
                                    type: string
                                  description: Display template for morning and afternoon
                                textColor:
                                  type: string
                                  description: >-
                                    The foreground color of the text, the
                                    default #FF0000
                                fontSize:
                                  type: integer
                                  description: Font size by pixels, the default is 16
                                fontFamily:
                                  type: array
                                  items:
                                    type: string
                                  description: >-
                                    Font type array, when there are multiple
                                    fonts, the first one takes precedence, if
                                    the first one is invalid, then the following
                                    font is taken in turn, if there is no such
                                    font, the system default one is used. For
                                    example: ["Times","Georia","New York"]
                                fontStyle:
                                  type: string
                                  description: >-
                                    Font type: 1.BOLD, 2.NORMAL, 3.ITALIC,
                                    4.BOLD_ITALIC
                                fontIsUnderline:
                                  type: boolean
                                  description: Is the font underlined or not
                                backgroundColor:
                                  type: string
                                  description: 'Background color, default #00FFFFFF'
                                shadowEnable:
                                  type: boolean
                                  description: Whether to enable shadows, default is false
                                shadowRadius:
                                  type: integer
                                  description: Shadow radius size in pixels
                                shadowDx:
                                  type: integer
                                  description: Shadow offset of the x-axis
                                shadowDy:
                                  type: integer
                                  description: Shadow offset of the y-axis
                                shadowColor:
                                  type: string
                                  description: Shadow color
                              x-apifox-refs: {}
                              x-apifox-orders:
                                - zIndex
                                - type
                                - zone
                                - gmt
                                - regular
                                - weekTemplates
                                - suffixTemplates
                                - textColor
                                - fontSize
                                - fontFamily
                                - fontStyle
                                - fontIsUnderline
                                - backgroundColor
                                - shadowEnable
                                - shadowRadius
                                - shadowDx
                                - shadowDy
                                - shadowColor
                                - layout
                              required:
                                - layout
                                - suffixTemplates
                                - weekTemplates
                                - regular
                                - gmt
                                - type
                                - fontStyle
                                - fontIsUnderline
                                - backgroundColor
                                - shadowEnable
                                - shadowDy
                                - shadowDx
                                - shadowRadius
                                - shadowColor
                                - zone
                                - textColor
                                - fontSize
                                - fontFamily
                                - zIndex
                              description: 'Widget type: DRAWN_DIGITAL_CLOCK'
                            - type: object
                              properties: {}
                              x-apifox-orders: []
                              description: Other widget type will coming soon
                        description: >-
                          Widgets on a page.  

                          Supported Widget Types: (PICTURE | GIF | VIDEO |
                          ARCH_TEXT  | SIMPLE_RSS | HTML | STREAM_MEDIA | BOX |
                          WEATHER |  DRAWN_DIGITAL_CLOCK |  RT_MEDIA)

                          Each element in the array is dynamic type (AnyOf), it
                          may be any of the following subtypes, specifically
                          according to the type within it.
                      repeatCount:
                        type: integer
                        description: 'Page Repeat Playback Count: Default: 1, Range: 1-100'
                    x-apifox-refs: {}
                    x-apifox-orders:
                      - name
                      - schedules
                      - widgets
                      - repeatCount
                    required:
                      - name
                      - widgets
                    description: contents to be played
                  description: A collection of the contents to be played.
              x-apifox-orders:
                - playerIds
                - schedule
                - pages
                - noticeUrl
              required:
                - playerIds
                - pages
            examples:
              '1':
                value:
                  playerIds:
                    - 553cbfe2ff4ad2e0d6bd89bb2c4e85e2
                  schedule:
                    startDate: '2020-04-11'
                    endDate: '2060-05-12'
                    plans:
                      - weekDays:
                          - 1
                          - 2
                          - 3
                          - 4
                          - 5
                        startTime: '00:00:00'
                        endTime: '22:00:00'
                      - weekDays:
                          - 0
                          - 6
                        startTime: '00:33:00'
                        endTime: '22:00:00'
                  pages:
                    - name: a-page
                      repeatCount: 1
                      widgets:
                        - zIndex: 1
                          type: PICTURE
                          size: 25943
                          md5: 8330dcaa949ceeafa54a66e8ad623300
                          duration: 10000
                          url: >-
                            http://vnnox-public.oss-cn-qingdao.aliyuncs.com/myf/test.jpg
                          layout:
                            x: 0%
                            'y': 0%
                            width: 100%
                            height: 100%
                          inAnimation:
                            type: NONE
                            duration: 1000
                        - zIndex: 2
                          type: VIDEO
                          size: 1227710
                          md5: f5b0f315800cb4befb89b5dff42f1e34
                          duration: 0
                          url: >-
                            http://vnnox-public.oss-cn-qingdao.aliyuncs.com/myf/nova2019.mp4
                          layout:
                            x: 0%
                            'y': 20%
                            width: 20%
                            height: 20%
                summary: Video images
              '2':
                value:
                  playerIds:
                    - f6535700b9613349b915135919a8dfcd
                  pages:
                    - name: page1
                      schedules:
                        - startDate: '2022-06-01'
                          endDate: '2088-06-30'
                          plans:
                            - weekDays:
                                - 1
                                - 2
                                - 3
                                - 4
                                - 5
                              startTime: '05:00:00'
                              endTime: '18:00:00'
                      widgets:
                        - zIndex: 1
                          type: PICTURE
                          size: 25943
                          md5: 8330dcaa949ceeafa54a66e8ad623300
                          duration: 5000
                          url: >-
                            http://vnnox-public.oss-cn-qingdao.aliyuncs.com/myf/test.jpg
                          layout:
                            x: 0%
                            'y': 0%
                            width: 100%
                            height: 100%
                          inAnimation:
                            type: NONE
                            duration: 1000
                    - name: page2
                      schedules:
                        - startDate: '2022-06-01'
                          endDate: '2088-06-30'
                          plans:
                            - weekDays:
                                - 1
                                - 2
                                - 3
                                - 4
                                - 5
                              startTime: '05:00:00'
                              endTime: '19:00:00'
                      widgets:
                        - zIndex: 1
                          type: VIDEO
                          size: 1227710
                          md5: f5b0f315800cb4befb89b5dff42f1e34
                          duration: 5000
                          url: >-
                            http://vnnox-public.oss-cn-qingdao.aliyuncs.com/myf/nova2019.mp4
                          fileName: 223344.flv
                          layout:
                            x: 54.296%
                            'y': 51.015%
                            width: 30%
                            height: 30%
                    - name: page3
                      schedules:
                        - startDate: '2022-06-01'
                          endDate: '2088-06-30'
                          plans:
                            - weekDays:
                                - 1
                                - 2
                                - 3
                                - 4
                                - 5
                              startTime: '05:00:00'
                              endTime: '19:00:00'
                      widgets:
                        - zIndex: 1
                          type: SIMPLE_RSS
                          duration: 5000
                          url: http://tech.qq.com/web/it/telerss.xml
                          updatePeriod: 22000
                          titleEnable: true
                          pubTimeEnable: true
                          bodyEnable: true
                          bodyImageEnable: false
                          displayType: PAGE_SWITCH
                          inAnimation:
                            type: RANDOM
                            duration: 9999
                          scrollAttribute:
                            animation: MARQUEE_LEFT
                            speed: 3
                            isHeadTail: true
                          pageSwitchAttribute:
                            inAnimation:
                              type: RANDOM
                              duration: 999
                            remainDuration: 10000
                          titleTextAttr:
                            textColor: '#1659C4'
                            fontSize: 16
                          pubTimeTextAttr:
                            textColor: '#FFFF00'
                            fontSize: 12
                          bodyTextAttr:
                            textColor: '#00FF00'
                            fontSize: 12
                          layout:
                            x: 50%
                            'y': 50%
                            width: 50%
                            height: 50%
                    - name: page4
                      schedules:
                        - startDate: '2022-06-01'
                          endDate: '2088-06-30'
                          plans:
                            - weekDays:
                                - 1
                                - 2
                                - 3
                                - 4
                                - 5
                              startTime: '05:00:00'
                              endTime: '17:00:00'
                      widgets:
                        - zIndex: 1
                          type: ARCH_TEXT
                          displayType: SCROLL
                          backgroundColor: '#00000000'
                          scrollAttribute:
                            animation: MARQUEE_LEFT
                            speed: 3
                            isHeadTail: true
                          duration: 5000
                          lines:
                            - textAttributes:
                                - content: API
                                  fontSize: 26
                                  textColor: '#337FE5'
                                  isBold: true
                                - content: The following characters
                                  fontSize: 16
                                  textColor: '#FF0000'
                                  isBold: false
                            - textAttributes:
                                - content: >-
                                    Hello, I am the main content of this
                                    message.
                                  fontSize: 16
                                  textColor: '#FF0000'
                                  isUnderline: false
                          layout:
                            x: 0%
                            'y': 90%
                            width: 100%
                            height: 10%
                    - name: 页面5
                      schedules:
                        - startDate: '2022-06-01'
                          endDate: '2088-06-30'
                          plans:
                            - weekDays:
                                - 1
                                - 2
                                - 3
                                - 4
                                - 5
                              startTime: '05:00:00'
                              endTime: '20:00:00'
                      widgets:
                        - zIndex: 1
                          type: HTML
                          duration: 5000
                          url: http://m.baidu.com
                          layout:
                            x: 0%
                            'y': 50%
                            width: 50%
                            height: 50%
                summary: Example of playback schedule protocol setting for each page
              '3':
                value:
                  playerIds:
                    - 553cbfe2ff4ad2e0d6bd89bb2c4e85e2
                  schedule:
                    startDate: '2020-04-11'
                    endDate: '2060-05-12'
                    plans:
                      - weekDays:
                          - 1
                          - 2
                          - 3
                          - 4
                          - 5
                        startTime: '00:00:01'
                        endTime: '22:00:00'
                      - weekDays:
                          - 0
                          - 6
                        startTime: '00:33:00'
                        endTime: '22:00:00'
                  pages:
                    - name: a-page
                      widgets:
                        - zIndex: 1
                          type: PICTURE
                          size: 25943
                          md5: 8330dcaa949ceeafa54a66e8ad623300
                          duration: 10000
                          url: >-
                            http://vnnox-public.oss-cn-qingdao.aliyuncs.com/myf/test.jpg
                          layout:
                            x: 0%
                            'y': 0%
                            width: 100%
                            height: 100%
                          inAnimation:
                            type: NONE
                            duration: 1000
                        - zIndex: 2
                          type: VIDEO
                          size: 1227710
                          md5: f5b0f315800cb4befb89b5dff42f1e34
                          duration: 0
                          url: >-
                            http://vnnox-public.oss-cn-qingdao.aliyuncs.com/myf/nova2019.mp4
                          layout:
                            x: 0%
                            'y': 20%
                            width: 20%
                            height: 20%
                        - zIndex: 4
                          type: SIMPLE_RSS
                          duration: 200000
                          url: http://tech.qq.com/web/it/telerss.xml
                          updatePeriod: 22000
                          titleEnable: true
                          pubTimeEnable: true
                          bodyEnable: true
                          bodyImageEnable: false
                          displayType: PAGE_SWITCH
                          inAnimation:
                            type: RANDOM
                            duration: 9999
                          scrollAttribute:
                            animation: MARQUEE_LEFT
                            speed: 3
                            isHeadTail: true
                          pageSwitchAttribute:
                            inAnimation:
                              type: RANDOM
                              duration: 999
                            remainDuration: 10000
                          titleTextAttr:
                            textColor: '#1659C4'
                            fontSize: 16
                          pubTimeTextAttr:
                            textColor: '#FFFF00'
                            fontSize: 12
                          bodyTextAttr:
                            textColor: '#00FF00'
                            fontSize: 12
                          layout:
                            x: 50%
                            'y': 50%
                            width: 50%
                            height: 50%
                        - zIndex: 5
                          type: HTML
                          duration: 50000
                          url: http://m.baidu.com
                          layout:
                            x: 0%
                            'y': 50%
                            width: 50%
                            height: 50%
                        - zIndex: 3
                          type: ARCH_TEXT
                          displayType: SCROLL
                          backgroundColor: '#00000000'
                          scrollAttribute:
                            animation: MARQUEE_LEFT
                            speed: 3
                            isHeadTail: true
                          duration: 200000
                          lines:
                            - textAttributes:
                                - content: API
                                  fontSize: 26
                                  textColor: '#337FE5'
                                  isBold: true
                                - content: The following characters
                                  fontSize: 16
                                  textColor: '#FF0000'
                                  isBold: false
                            - textAttributes:
                                - content: >-
                                    Hello, I am the main content of this
                                    message.
                                  fontSize: 16
                                  textColor: '#FF0000'
                                  isUnderline: false
                          layout:
                            x: 10%
                            'y': 0%
                            width: 90%
                            height: 20%
                summary: >-
                  The entire program presents an example of a playback schedule
                  agreement
              '4':
                value:
                  playerIds:
                    - 59773cddc1b23b62f4233a5e75780ac8
                  pages:
                    - name: a-page
                      widgets:
                        - zIndex: 1
                          type: BOX
                          layout:
                            x: 0%
                            'y': 0%
                            width: 100%
                            height: 90%
                          mediaList:
                            - zIndex: 1
                              type: PICTURE
                              size: 25943
                              md5: 8330dcaa949ceeafa54a66e8ad623300
                              duration: 10000
                              url: >-
                                http://vnnox-public.oss-cn-qingdao.aliyuncs.com/myf/test.jpg
                              inAnimation:
                                type: NONE
                                duration: 1000
                            - zIndex: 2
                              type: VIDEO
                              size: 1227710
                              md5: f5b0f315800cb4befb89b5dff42f1e34
                              duration: 0
                              url: >-
                                http://vnnox-public.oss-cn-qingdao.aliyuncs.com/myf/nova2019.mp4
                            - zIndex: 5
                              type: HTML
                              duration: 50000
                              url: http://m.baidu.com
                        - zIndex: 3
                          type: ARCH_TEXT
                          displayType: SCROLL
                          backgroundColor: '#00000000'
                          scrollAttribute:
                            animation: MARQUEE_LEFT
                            speed: 3
                            isHeadTail: true
                          duration: 200000
                          lines:
                            - textAttributes:
                                - content: API
                                  fontSize: 26
                                  textColor: '#337FE5'
                                  isBold: true
                                - content: The following characters
                                  fontSize: 16
                                  textColor: '#FF0000'
                                  isBold: false
                            - textAttributes:
                                - content: >-
                                    Hello, I am the main content of this
                                    message.
                                  fontSize: 16
                                  textColor: '#FF0000'
                                  isUnderline: false
                          layout:
                            x: 0%
                            'y': 90%
                            width: 100%
                            height: 10%
                summary: Window program example protocol example
              '5':
                value:
                  playerIds:
                    - 8cf704bc45edd81118bd2e97dabc54b1
                    - df8b3c84cc253b436c945422c706bda1
                  attribute:
                    normalProgramStatus: NORMAL
                  pages:
                    - name: a-page
                      widgets:
                        - zIndex: 1
                          type: STREAM_MEDIA
                          duration: 500000
                          url: https://media.w3.org/2010/05/sintel/trailer.mp4
                          layout:
                            x: 0%
                            'y': 50%
                            width: 50%
                            height: 50%
                summary: Streaming Program Agreement Example
              '6':
                value:
                  playerIds:
                    - 49c0a2a55734fa9cdc8a3d1a24a6eba2
                  pages:
                    - name: Simple Weather Component
                      widgets:
                        - zIndex: 1
                          type: WEATHER
                          address: Qinghai Province
                          latitude: 36.962165
                          longitude: 96.27126
                          width: 147
                          height: 140
                          refreshPeriod: 600000
                          fontSize: 18
                          bold: true
                          italic: true
                          underline: true
                          color: '#F90840'
                          tempUnit: 0
                          unitSymbol: 0
                          weatherEnable: true
                          tempEnable: true
                          windEnable: true
                          humidEnable: true
                          currentTempEnable: true
                          isShowInOneLine: false
                          duration: 10000
                          layout:
                            x: 0%
                            'y': 0%
                            width: 50%
                            height: 50%
                summary: Simple Weather Component Program Protocol Example
              '7':
                value:
                  playerIds:
                    - 49c0a2a55734fa9cdc8a3d1a24a6eba2
                  pages:
                    - name: a-page
                      widgets:
                        - zIndex: 1
                          type: DRAWN_DIGITAL_CLOCK
                          zone: Asia/Shanghai
                          gmt: GMT+08:00
                          regular: |-
                            $MM/$dd/$yyyy
                            $E
                            $N $hh:$mm:$ss
                          weekTemplates:
                            - Sunday
                            - Monday
                            - Tuesday
                            - Wednesday
                            - Thursday
                            - Friday
                            - Saturday
                          suffixTemplates:
                            - forenoon
                            - afternoon
                          textColor: '#ff0000'
                          fontSize: 14
                          fontFamily:
                            - Times
                            - Georia
                            - New York
                          fontStyle: Bold
                          fontIsUnderline: false
                          backgroundColor: '#00ff00'
                          shadowEnable: false
                          shadowRadius: 10
                          shadowDx: 2
                          shadowDy: 2
                          shadowColor: '#00ff00'
                          layout:
                            x: 0%
                            'y': 0%
                            width: 100%
                            height: 100%
                summary: Example of digital clock component program delivery
              '8':
                value:
                  programType: 1
                  planVersion: V2
                  schedule:
                    startDate: '2020-04-11'
                    endDate: '2060-05-12'
                    plans:
                      - weekDays:
                          - 0
                          - 1
                          - 2
                          - 3
                          - 4
                          - 5
                          - 6
                        startTime: '00:00:00'
                        endTime: '22:00:00'
                  pages:
                    - name: a-page
                      widgets:
                        - zIndex: 1
                          type: DRAWN_DIGITAL_CLOCK
                          zone: Asia/Shanghai
                          gmt: GMT+08:00
                          regular: |-
                            $MM/$dd/$yyyy
                            $E
                            $N $hh:$mm:$ss
                          weekTemplates:
                            - Sunday
                            - Monday
                            - Tuesday
                            - Wednesday
                            - Thursday
                            - Friday
                            - Saturday
                          suffixTemplates:
                            - forenoon
                            - afternoon
                          textColor: '#ff0000'
                          fontSize: 14
                          fontFamily:
                            - Times
                            - Georia
                            - New York
                          fontStyle: Bold
                          fontIsUnderline: false
                          backgroundColor: '#00ff00'
                          shadowEnable: false
                          shadowRadius: 10
                          shadowDx: 2
                          shadowDy: 2
                          shadowColor: '#00ff00'
                          layout:
                            x: 0%
                            'y': 0%
                            width: 100%
                            height: 100%
                summary: Example of offline export for digital clock component program
              '9':
                value:
                  playerIds:
                    - 49c0a2a55734fa9cdc8a3d1a24a6eba2
                  pages:
                    - name: Basic Weather Component
                      widgets:
                        - lang: en
                          zIndex: 1
                          type: ANALOG_WEATHER
                          address: XIAN city
                          longitude: 108.946252
                          latitude: 34.348475
                          width: 500
                          height: 500
                          refreshPeriod: 600000
                          tempUnit: 0
                          duration: 100000
                          layout:
                            x: 0%
                            'y': 0%
                            width: 50%
                            height: 50%
                summary: Example of basic weather program component
              '10':
                value:
                  playerIds:
                    - 49c0a2a55734fa9cdc8a3d1a24a6eba2
                  pages:
                    - name: Advanced Weather Component
                      widgets:
                        - lang: zh
                          zIndex: 1
                          type: ADVANCED_WEATHER
                          address: Xi'an City, Shaanxi Province
                          longitude: 108.946252
                          latitude: 34.348475
                          width: 800
                          height: 800
                          refreshPeriod: 600000
                          pageDuration: 100000
                          tempUnit: 0
                          module: 1
                          basicInfo: true
                          airQuality: true
                          comfort: true
                          windSpeed: true
                          sunrise: true
                          living: true
                          duration: 100000
                          layout:
                            x: 0%
                            'y': 0%
                            width: 50%
                            height: 100%
                summary: Example of an advanced weather program component
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                  fail:
                    type: array
                    items:
                      type: string
                required:
                  - success
                  - fail
                x-apifox-orders:
                  - success
                  - fail
              example:
                success:
                  - 8cd86b94c6617c5d771fb91224b45685
                fail: []
          headers: {}
          x-apifox-name: OK
      security: []
      x-apifox-folder: VNNOX/Solutions
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-180502121-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```



# Offline Export

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/program/offline-export:
    post:
      summary: Offline Export
      deprecated: false
      description: >-
        1.Through this interface, you can export Nova playback protocol for
        offline programs

        2.Support T card version 2.0.0 and above, vPlayer version 4.0.0 and
        above
      tags:
        - VNNOX/Solutions
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                programType:
                  type: integer
                  description: Program type, 1- Normal program
                planVersion:
                  type: string
                  description: >-
                    Protocol version: V1, V2; If the T card version is greater
                    than 3.10.0.0601 or the V card version is greater than
                    4.1.1.0601, the V2 protocol is used when multiple lines of
                    text are delivered
                schedule:
                  type: object
                  properties:
                    startDate:
                      type: string
                      description: 'Playback start date, such as: 2020-04-12'
                    endDate:
                      type: string
                      description: 'Playback end date, such as: 2020-04-12'
                    plans:
                      type: object
                      properties:
                        weekDays:
                          type: array
                          items:
                            type: integer
                          description: >-
                            Play date within the week, 0-Sunday, 1-Monday,
                            2-Tuesday, 3-Wednesday, 4-Thursday, 5-Friday,
                            6-Saturday
                        startTime:
                          type: string
                          description: 'Specific start time of the day, such as: 08:00'
                        endTime:
                          type: string
                          description: 'The specific end time of the day, such as: 18:00'
                      required:
                        - weekDays
                        - startTime
                        - endTime
                      x-apifox-orders:
                        - weekDays
                        - startTime
                        - endTime
                      description: Detailed playback plan
                  required:
                    - startDate
                    - endDate
                    - plans
                  x-apifox-orders:
                    - startDate
                    - endDate
                    - plans
                  description: >-
                    The time schedule of the whole program, if empty, will be
                    played on a loop 24 hours a day;  If both the program level
                    schedule and page level schedule are set at the same time,
                    the playback will be subject to the intersection of the
                    schedule
                pages:
                  type: array
                  items:
                    type: object
                    properties:
                      name:
                        type: string
                        description: Page name
                      widgets:
                        type: array
                        items:
                          type: object
                          properties: {}
                          x-apifox-orders: []
                          description: Program component array
                        description: >-
                          Component array, specific reference: Program component
                          catalog
                    x-apifox-orders:
                      - name
                      - widgets
                    description: The page content that needs to be played
                  description: A collection of page content to be played
              required:
                - programType
                - planVersion
                - schedule
                - pages
              x-apifox-orders:
                - programType
                - planVersion
                - schedule
                - pages
            example:
              programType: 1
              planVersion: V2
              schedule:
                startDate: '2020-04-11'
                endDate: '2060-05-12'
                plans:
                  - weekDays:
                      - 0
                      - 1
                      - 2
                      - 3
                      - 4
                      - 5
                      - 6
                    startTime: '00:00'
                    endTime: '23:00'
              pages:
                - name: a-page
                  widgets:
                    - zIndex: 1
                      type: DRAWN_DIGITAL_CLOCK
                      zone: Asia/Shanghai
                      gmt: GMT+08:00
                      regular: |-
                        $MM/$dd/$yyyy
                        $E
                        $N&#160;$hh:$mm:$ss
                      weekTemplates:
                        - Sunday
                        - Monday
                        - Tuesday
                        - Wednesday
                        - Thursday
                        - Friday
                        - Saturday
                      suffixTemplates:
                        - morning
                        - afternoon
                      textColor: '#ff0000'
                      fontSize: 14
                      fontFamily:
                        - Times
                        - Georia
                        - New York
                      fontStyle: Bold
                      fontIsUnderline: false
                      backgroundColor: '#00ff00'
                      shadowEnable: false
                      shadowRadius: 10
                      shadowDx: 2
                      shadowDy: 2
                      shadowColor: '#00ff00'
                      layout:
                        x: 0%
                        'y': 0%
                        width: 50%
                        height: 50%
                    - zIndex: 2
                      type: PICTURE
                      size: 25943
                      md5: 8330dcaa949ceeafa54a66e8ad623300
                      duration: 5000
                      url: test.jpg
                      layout:
                        x: 50%
                        'y': 0%
                        width: 50%
                        height: 50%
                      inAnimation:
                        type: NONE
                        duration: 1000
                    - zIndex: 3
                      type: VIDEO
                      size: 1227710
                      md5: f5b0f315800cb4befb89b5dff42f1e34
                      duration: 5000
                      url: nova2019.mp4
                      fileName: nova2019.mp4
                      layout:
                        x: 0%
                        'y': 50%
                        width: 50%
                        height: 50%
                    - zIndex: 4
                      type: ARCH_TEXT
                      displayType: SCROLL
                      backgroundColor: '#00000000'
                      scrollAttribute:
                        animation: MARQUEE_LEFT
                        speed: 3
                        isHeadTail: true
                      duration: 5000
                      lines:
                        - textAttributes:
                            - content: API
                              fontSize: 26
                              textColor: '#337FE5'
                              isBold: true
                            - content: some following content
                              fontSize: 16
                              textColor: '#FF0000'
                              isBold: false
                        - textAttributes:
                            - content: some main content
                              fontSize: 16
                              textColor: '#FF0000'
                              isUnderline: false
                      layout:
                        x: 50%
                        'y': 50%
                        width: 50%
                        height: 50%
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  displaySolutions:
                    type: object
                    properties:
                      md5:
                        type: string
                      fileName:
                        type: string
                      url:
                        type: string
                    required:
                      - md5
                      - fileName
                      - url
                    x-apifox-orders:
                      - md5
                      - fileName
                      - url
                  playRelations:
                    type: object
                    properties:
                      md5:
                        type: string
                      fileName:
                        type: string
                      url:
                        type: string
                    required:
                      - md5
                      - fileName
                      - url
                    x-apifox-orders:
                      - md5
                      - fileName
                      - url
                  playSolutions:
                    type: object
                    properties:
                      md5:
                        type: string
                      fileName:
                        type: string
                      url:
                        type: string
                    required:
                      - md5
                      - fileName
                      - url
                    x-apifox-orders:
                      - md5
                      - fileName
                      - url
                  playlists:
                    type: object
                    properties:
                      md5:
                        type: string
                      fileName:
                        type: string
                      url:
                        type: string
                    required:
                      - md5
                      - fileName
                      - url
                    x-apifox-orders:
                      - md5
                      - fileName
                      - url
                  scheduleConstraints:
                    type: object
                    properties:
                      md5:
                        type: string
                      fileName:
                        type: string
                      url:
                        type: string
                    required:
                      - md5
                      - fileName
                      - url
                    x-apifox-orders:
                      - md5
                      - fileName
                      - url
                  planJson:
                    type: object
                    properties:
                      md5:
                        type: string
                      fileName:
                        type: string
                      url:
                        type: string
                      isSupportMd5Checkout:
                        type: boolean
                      programName:
                        type: string
                    required:
                      - md5
                      - fileName
                      - url
                      - isSupportMd5Checkout
                      - programName
                    x-apifox-orders:
                      - md5
                      - fileName
                      - url
                      - isSupportMd5Checkout
                      - programName
                required:
                  - displaySolutions
                  - playRelations
                  - playSolutions
                  - playlists
                  - scheduleConstraints
                  - planJson
                x-apifox-orders:
                  - displaySolutions
                  - playRelations
                  - playSolutions
                  - playlists
                  - scheduleConstraints
                  - planJson
              example:
                displaySolutions:
                  md5: 1376da5787a1008d875c01af893a6900
                  fileName: display_solution.json
                  url: >-
                    https://novacloud-dev.oss-cn-hangzhou.aliyuncs.com/pingjl_sit/api/normal/2024-09-24/1727157294527/display_solution.json?OSSAccessKeyId=LTAI5tE3ck6A2DexBqttYHVH&Expires=1727762095&Signature=sCRWAV4%2B1vref8IV0H4sXyCViDk%3D
                playRelations:
                  md5: a36aba42bcf94b63c0ef9abadc8feaf8
                  fileName: RelationDescription.json
                  url: >-
                    https://novacloud-dev.oss-cn-hangzhou.aliyuncs.com/pingjl_sit/api/normal/2024-09-24/1727157294527/RelationDescription.json?OSSAccessKeyId=LTAI5tE3ck6A2DexBqttYHVH&Expires=1727762095&Signature=pAbPAjVvJeHXodJYnTKDC6t26cs%3D
                playSolutions:
                  md5: 336459f8f6646685414d815e23b9dd6e
                  fileName: play_solution0.json
                  url: >-
                    https://novacloud-dev.oss-cn-hangzhou.aliyuncs.com/pingjl_sit/api/normal/2024-09-24/1727157294527/play_solution0.json?OSSAccessKeyId=LTAI5tE3ck6A2DexBqttYHVH&Expires=1727762095&Signature=8Uv2D9jc0uFF31VuEfFEuHayeVw%3D
                playlists:
                  md5: 6094b47417d0b8e26e1f0ae86b95304b
                  fileName: playlist0.json
                  url: >-
                    https://novacloud-dev.oss-cn-hangzhou.aliyuncs.com/pingjl_sit/api/normal/2024-09-24/1727157294527/playlist0.json?OSSAccessKeyId=LTAI5tE3ck6A2DexBqttYHVH&Expires=1727762095&Signature=Gfi6as9VQ7gHoRxhCz4qa1NFR7g%3D
                scheduleConstraints:
                  md5: d72378022149d7f0e7f6a7ead62e7081
                  fileName: schedule_constraint0.json
                  url: >-
                    https://novacloud-dev.oss-cn-hangzhou.aliyuncs.com/pingjl_sit/api/normal/2024-09-24/1727157294527/schedule_constraint0.json?OSSAccessKeyId=LTAI5tE3ck6A2DexBqttYHVH&Expires=1727762095&Signature=M6IGH6QwA5PRWO%2Fy48MAMb%2Fl70I%3D
                planJson:
                  md5: 5c134f93d12cd3b7fe4c5f29cbb47beb
                  fileName: planlist.json
                  url: >-
                    https://novacloud-dev.oss-cn-hangzhou.aliyuncs.com/pingjl_sit/api/normal/2024-09-24/planlist.json?OSSAccessKeyId=LTAI5tE3ck6A2DexBqttYHVH&Expires=1727762095&Signature=37sPnng0AWjCYnLSYoACdpuNrEA%3D
                  isSupportMd5Checkout: false
                  programName: API-202409241354552904-Program
          headers: {}
          x-apifox-name: OK
      security: []
      x-apifox-folder: VNNOX/Solutions
      x-apifox-status: developing
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-277397809-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```

# Over-specification Detection Switching

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/immediateControl/over-specification-options:
    post:
      summary: Over-specification Detection Switching
      deprecated: false
      description: >-
        Use this API to control the player to enable or disable
        over-specification detection
      tags:
        - VNNOX/Solutions
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                enable:
                  type: boolean
              required:
                - playerIds
                - enable
              x-apifox-orders:
                - playerIds
                - enable
            example:
              playerIds:
                - 8208967d40e9980bab6d12367dc88e0b
              enable: false
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                  fail:
                    type: array
                    items:
                      type: string
                required:
                  - success
                  - fail
                x-apifox-orders:
                  - success
                  - fail
              example:
                success:
                  - 4PBXun3mQoZGnKdLKoDtBA==
                  - 4PBXun32QoZGnKdLKoDtBA==
                fail:
                  - YkCun3mQoZGnKdLKoDm65==
                  - YkCun32QoZGnKdLKoDc92==
          headers: {}
          x-apifox-name: OK
      security: []
      x-apifox-folder: VNNOX/Solutions
      x-apifox-status: developing
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-277399402-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Program Over-specification Detection

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/program/over-specification-check:
    post:
      summary: Program Over-specification Detection
      deprecated: false
      description: >-
        1.This interface is used to check whether the program sent to the
        required devices will be any over-specification

        2.The over-spec standard of the image is that the width is not more than
        2160 px, the height is not more than 4096 px, and the image area cannot
        exceed 2160x4096
      tags:
        - VNNOX/Solutions
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                pages:
                  type: array
                  items:
                    type: object
                    properties:
                      pageId:
                        type: integer
                      name:
                        type: string
                      widgets:
                        type: array
                        items:
                          type: object
                          properties:
                            widgetId:
                              type: integer
                            type:
                              type: string
                              description: |-
                                Component type
                                PICTURE - picture
                                GIF - Picture
                                VIDEO- Video
                            size:
                              type: integer
                              description: Image or video size byte
                            md5:
                              type: string
                            url:
                              type: string
                            width:
                              type: string
                            height:
                              type: string
                            duration:
                              type: integer
                            fps:
                              type: string
                            byteRate:
                              type: string
                              description: >-
                                Video bit rate in large M- Required for video
                                only
                            codec:
                              type: string
                              description: >-
                                Video encoding format, such as: h264,h265- Video
                                only required
                            postfix:
                              type: string
                              description: >-
                                Video file suffix, such as: mp4- video only
                                required
                            name:
                              type: string
                              description: >-
                                Component name, which is used for log query. If
                                you do not enter this parameter, it is difficult
                                to distinguish between components during log
                                query
                          required:
                            - widgetId
                            - type
                            - size
                            - md5
                            - url
                            - width
                            - height
                            - duration
                            - fps
                            - byteRate
                            - codec
                            - postfix
                            - name
                          x-apifox-orders:
                            - widgetId
                            - type
                            - size
                            - md5
                            - url
                            - width
                            - height
                            - duration
                            - fps
                            - byteRate
                            - codec
                            - postfix
                            - name
                    required:
                      - pageId
                      - name
                      - widgets
                    x-apifox-orders:
                      - pageId
                      - name
                      - widgets
              required:
                - playerIds
                - pages
              x-apifox-orders:
                - playerIds
                - pages
            example:
              playerIds:
                - f6535700b9613349b915135919a8dfcd
                - fdddddddddd13349b915135919a8dfce
              pages:
                - pageId: 1
                  name: 页面1
                  widgets:
                    - widgetId: 1
                      type: PICTURE
                      size: 25943
                      md5: 8330dcaa949ceeafa54a66e8ad623300
                      url: >-
                        http://vnnox-public.oss-cn-qingdao.aliyuncs.com/myf/test.jpg
                      width: '3840'
                      height: '2160'
                    - widgetId: 2
                      type: PICTURE
                      size: 25943
                      md5: 8330dcaa949ceeafa54a66e8ad623300
                      url: >-
                        http://vnnox-public.oss-cn-qingdao.aliyuncs.com/myf/test.jpg
                      width: '3840'
                      height: '2160'
                - pageId: 2
                  name: 新页面2
                  widgets:
                    - widgetId: 3
                      type: VIDEO
                      size: 1227710
                      md5: f5b0f315800cb4befb89b5dff42f1e34
                      duration: 5000
                      url: >-
                        http://vnnox-public.oss-cn-qingdao.aliyuncs.com/myf/nova2019.mp4
                      width: '3840'
                      height: '2160'
                      fps: '60'
                      byteRate: '18003'
                      codec: h264
                      postfix: mp4
                    - widgetId: 4
                      type: VIDEO
                      size: 1227710
                      md5: f5b0f315800cb4befb89b5dff42f1e34
                      duration: 5000
                      url: >-
                        http://vnnox-public.oss-cn-qingdao.aliyuncs.com/myf/nova2019.mp4
                      width: '3840'
                      height: '2160'
                      fps: '60'
                      byteRate: '18003'
                      codec: h264
                      postfix: mp4
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  logid:
                    type: integer
                  status:
                    type: integer
                  data:
                    type: array
                    items:
                      type: object
                      properties:
                        overSpec:
                          type: boolean
                          description: >-
                            The over-specification result of the batch test is
                            true- over-specification, false- no
                            over-specification
                        playerIds:
                          type: array
                          items:
                            type: string
                        overSpecType:
                          type: integer
                          description: >-
                            The "overSpecType" field in the return information
                            protocol indicates the type of program
                            superspecification


                            1 indicates that there is only a single media in the
                            program


                            2 indicates that the number of Windows in the
                            program exceeds the upper limit


                            3 Indicates the number of program specifications
                            media exceeds specifications (number of channels
                            exceeds specifications)


                            4 indicates that the program exists simultaneously


                            The values of type 1, 2, and 3 are out of
                            specification.
                        overSpecDetail:
                          type: array
                          items:
                            type: object
                            properties:
                              pageId:
                                type: integer
                              widgetId:
                                type: integer
                              overSpecErrorCode:
                                type: array
                                items:
                                  type: integer
                                description: >-
                                  -1 Video decoding is not supported


                                  -2 Video decoding name (CODEC_NAME) is
                                  incorrect


                                  -3 Video suffix is not supported


                                  -4 Video encoder label name (CODEC_TAG_STRING)
                                  is not supported


                                  -5 Video width is not supported


                                  -6 Video height is not supported


                                  -7 Video BIT is not supported


                                  -8 Video FRAME is not supported


                                  -9 Multi-channel video playback exceeds the
                                  upper limit


                                  -10 Causes the system to restart abnormally


                                  -11 Causes abnormal restart of playback


                                  -12 Low memory is generated


                                  -15 The UUIDBOXSIZE of MP4 is too large


                                  - The 16-bit depth is not supported


                                  -20 The frame rate exceeds the upper limit


                                  -21 bit rate exceeds the upper limit


                                  -22 The amount of media data exceeds the upper
                                  limit


                                  -23 The amount of media data in the specified
                                  encoding format exceeds the upper limit


                                  793 No video media type exists


                                  10001 Picture exceeds size
                              recommend:
                                type: object
                                properties:
                                  width:
                                    type: string
                                  height:
                                    type: string
                                  postfix:
                                    type: string
                                  fps:
                                    type: string
                                  byteRate:
                                    type: string
                                    description: >-
                                      Video bit rate, in large M- only video is
                                      returned
                                  codec:
                                    type: string
                                required:
                                  - width
                                  - height
                                x-apifox-orders:
                                  - width
                                  - height
                                  - postfix
                                  - fps
                                  - byteRate
                                  - codec
                                description: Recommended transcoding information
                            required:
                              - pageId
                              - widgetId
                              - overSpecErrorCode
                              - recommend
                            x-apifox-orders:
                              - pageId
                              - widgetId
                              - overSpecErrorCode
                              - recommend
                          description: >-
                            When the program is out of spec, this property
                            returns what content is out of spec. What is the
                            recommended transcoding format
                      required:
                        - overSpec
                        - playerIds
                      x-apifox-orders:
                        - overSpec
                        - playerIds
                        - overSpecType
                        - overSpecDetail
                required:
                  - logid
                  - status
                  - data
                x-apifox-orders:
                  - logid
                  - status
                  - data
              example:
                logid: 1588056252560
                status: 0
                data:
                  - overSpec: false
                    playerIds:
                      - fdddddddddd13349b915135919a8dfce
                  - overSpec: true
                    overSpecType: 1
                    overSpecDetail:
                      - pageId: 2
                        widgetId: 3
                        overSpecErrorCode:
                          - -20
                          - -21
                        recommend:
                          width: '3840'
                          height: '2160'
                          postfix: mp4
                          fps: '30'
                          byteRate: '78.000000'
                          codec: h264
                      - pageId: 2
                        widgetId: 4
                        overSpecErrorCode:
                          - -20
                          - -21
                        recommend:
                          width: '3840'
                          height: '2160'
                          postfix: mp4
                          fps: '30'
                          byteRate: '78.000000'
                          codec: h264
                      - pageId: 2
                        widgetId: 5
                        overSpecErrorCode:
                          - 10001
                        recommend:
                          width: '2160'
                          height: '4096'
                    playerIds:
                      - f6535700b9613349b915135919a8dfcd
          headers: {}
          x-apifox-name: OK
      security: []
      x-apifox-folder: VNNOX/Solutions
      x-apifox-status: developing
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-277411236-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```

# NTP Time Synchronization

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/real-time-control/ntp:
    post:
      summary: NTP Time Synchronization
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for NTP time synchronization settings.

        2. If you set RF time synchronization for a player and set the player as
        a slave device, setting NTP synchronization for the player will not be
        available. You need to turn off RF time synchronization and then set NTP
        time synchronization.

        3. Sub-accounts can only manage data within their specific workgroup and
        its sub-workgroups.

        4. Advanced interface.

        :::
      tags:
        - VNNOX/Real-Time Control
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                    description: player IDs
                  description: >-
                    A collection of player IDs to be handled. At most 100 player
                    IDs can be handled simultaneously.
                server:
                  type: string
                  description: >-
                    The node address for time synchronization is required. The
                    available addresses include China: ntp1.aliyun.com and US:
                    us.ntp.org.cn.
                enable:
                  type: boolean
                  description: 'Enable NTP time synchronization, true: Yes, false: No.'
              x-apifox-orders:
                - playerIds
                - server
                - enable
              required:
                - server
                - playerIds
                - enable
            example:
              playerIds:
                - df6c02352e4fd3cd5bc664fcdaef29c9
              server: ntp1.aliyun.com
              enable: true
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are successfully sent.
                  fail:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are not sent successfully.
                required:
                  - success
                  - fail
                x-apifox-orders:
                  - success
                  - fail
              example:
                success:
                  - df6c02352e4fd3cd5bc664fcdaef29c9
                fail: []
          headers: {}
          x-apifox-name: OK
      security: []
      x-apifox-folder: VNNOX/Real-Time Control
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-180502118-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```

# Synchronous playback

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/real-time-control/simulcast:
    post:
      summary: Synchronous playback
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for synchronous playback settings.

        2. Sub-accounts can only manage data within their specific workgroup and
        its sub-workgroups.

        3. Sub-accounts can only manage data within their specific workgroup and
        its sub-workgroups.

        4. Advanced interface.

        :::
      tags:
        - VNNOX/Real-Time Control
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                    description: player IDs
                  description: >-
                    A collection of player IDs to be handled. At most 100 player
                    IDs can be handled simultaneously.
                option:
                  type: integer
                  description: >-
                    Synchronous playback status 0-Turn off synchronous playback
                    1-Turn on synchronous playback.
              x-apifox-orders:
                - playerIds
                - option
              required:
                - option
                - playerIds
            example:
              playerIds:
                - 788fcd80eae568bd77e0ad6e9bcff405
                - a25e9cfb225c5fcb4d479b3d9509f09b
              option: 1
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are successfully sent.
                  fail:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are not sent successfully.
                required:
                  - success
                  - fail
                x-apifox-orders:
                  - success
                  - fail
              example:
                success:
                  - 788fcd80eae568bd77e0ad6e9bcff405
                  - a25e9cfb225c5fcb4d479b3d9509f09b
                fail: []
          headers: {}
          x-apifox-name: OK
      security: []
      x-apifox-folder: VNNOX/Real-Time Control
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-180502119-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Brightness Adjustment

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/real-time-control/brightness:
    post:
      summary: Brightness Adjustment
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for adjusting the screen brightness.

        2. Sub-accounts can only manage data within their specific workgroup and
        its sub-workgroups.

        3. Advanced interface.

        :::
      tags:
        - VNNOX/Real-Time Control
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                  description: >-
                    A collection of player IDs to be handled. At most 100 player
                    IDs can be handled simultaneously.
                value:
                  type: integer
                  description: 'Brightness percentage, range: 0~100.'
              x-apifox-orders:
                - playerIds
                - value
              required:
                - playerIds
                - value
            example:
              playerIds:
                - 4PBXun3mQoZGnKdLKoDtBA==
                - 4PBXun32QoZGnKdLKoDtBA==
              value: 50
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are successfully sent.
                  fail:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are not sent successfully.
                x-apifox-orders:
                  - success
                  - fail
                required:
                  - success
                  - fail
              example:
                success:
                  - 4PBXun3mQoZGnKdLKoDtBA==
                  - 4PBXun32QoZGnKdLKoDtBA==
                fail:
                  - 4PBXun3mQoZGnKdLKoDtBA==
                  - 4PBXun32QoZGnKdLKoDtBA==
          headers: {}
          x-apifox-name: OK
      security: []
      x-apifox-folder: VNNOX/Real-Time Control
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-180498655-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```



# Screenshots

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/real-time-control/screen-capture:
    post:
      summary: Screenshots
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for obtaining the real-time screenshots of the
        playback window.

        2. Sub-accounts can only manage data within their specific workgroup and
        its sub-workgroups.

        3. Advanced interface.

        :::
      tags:
        - VNNOX/Real-Time Control
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                    description: player IDs
                  description: >-
                    A collection of player IDs to be handled. At most 100 player
                    IDs can be handled simultaneously.
                noticeUrl:
                  type: string
                  description: >-
                    After a screenshot is captured successfully, this interface
                    will send the address of the screenshot to the customer in
                    the way of [POST-JSON]. The corresponding time of the
                    interface cannot exceed 3s.
              x-apifox-orders:
                - playerIds
                - noticeUrl
              required:
                - playerIds
                - noticeUrl
            example:
              playerIds:
                - df6c02352e4fd3cd5bc664fcdaef29c9
              noticeUrl: http://sit-api.vnnox.com/test/noticeScreenShotUrl
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                      description: player IDs
                    description: A collection of player IDs that are successfully sent.
                  fail:
                    type: array
                    items:
                      type: string
                      description: player IDs
                    description: A collection of player IDs that are not sent successfully.
                x-apifox-orders:
                  - success
                  - fail
                required:
                  - success
                  - fail
              example:
                success:
                  - df6c02352e4fd3cd5bc664fcdaef29c9
                fail: []
          headers: {}
          x-apifox-name: OK
        x-200:Call-back Request Parameters for Notifying Users of Screenshot Information:
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  playerId:
                    type: string
                    description: Corresponding player ID
                  playerTime:
                    type: string
                    description: Time of the current player
                  screenShotUrl:
                    type: string
                    description: >-
                      Address of the screenshot of the current player. The URL
                      of the screenshot is valid for 2 hours. Please download
                      the screenshot to your server within 2 hours.
                required:
                  - playerId
                  - playerTime
                  - screenShotUrl
                x-apifox-orders:
                  - playerId
                  - playerTime
                  - screenShotUrl
              example:
                playerId: df6c02352e4fd3cd5bc664fcdaef29c9
                playerTime: '2020-05-06 10:12:37'
                screenShotUrl: >-
                  https://novacloud-dev.oss-cn-hangzhou.aliyuncs.com/myfdev/screenshot/df6c02352e4fd3cd5bc664fcdaef29c9/20200506101237ff71eaf851c45f1b1de5e1516beffb7b.jpg?OSSAccessKeyId=LTAI4FwrvLWLuyvaNQNf4wB9&Expires=1588820157&Signature=A%2FCbpLpK1nxhfXEBauwX01tEJFc%3D
          headers: {}
          x-apifox-name: >-
            Call-back Request Parameters for Notifying Users of Screenshot
            Information
      security: []
      x-apifox-folder: VNNOX/Real-Time Control
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-180502120-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Volume Adjustment

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/real-time-control/volume:
    post:
      summary: Volume Adjustment
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for adjusting player volume.

        2. Sub-accounts can only manage data within their specific workgroup and
        its sub-workgroups.

        3. Advanced interface.

        :::
      tags:
        - VNNOX/Real-Time Control
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                  description: >-
                    A collection of player IDs to be handled. At most 100 player
                    IDs can be handled simultaneously.
                value:
                  type: integer
                  description: 'Volume percentage, range: 0~100.'
              x-apifox-orders:
                - playerIds
                - value
              required:
                - playerIds
                - value
            example:
              playerIds:
                - 4PBXun3mQoZGnKdLKoDtBA==
                - 4PBXun32QoZGnKdLKoDtBA==
              value: 50
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are successfully sent.
                  fail:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are not sent successfully.
                x-apifox-orders:
                  - success
                  - fail
                required:
                  - success
                  - fail
              example:
                success:
                  - 4PBXun3mQoZGnKdLKoDtBA==
                  - 4PBXun32QoZGnKdLKoDtBA==
                fail:
                  - 4PBXun3mQoZGnKdLKoDtBA==
                  - 4PBXun32QoZGnKdLKoDtBA==
          headers: {}
          x-apifox-name: OK
      security: []
      x-apifox-folder: VNNOX/Real-Time Control
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-180498656-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Video Source Switching

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/real-time-control/video-source:
    post:
      summary: Video Source Switching
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for switching between the internal and
        external sources of a player.

        2. Sub-accounts can only manage data within their specific workgroup and
        its sub-workgroups.

        3. Advanced interface.

        :::
      tags:
        - VNNOX/Real-Time Control
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                  description: >-
                    A collection of player IDs to be handled. At most 100 player
                    IDs can be handled simultaneously.
                source:
                  type: integer
                  description: Control video source，0-Internal source，1-External Source.
              x-apifox-orders:
                - playerIds
                - source
              required:
                - playerIds
                - source
            example:
              playerIds:
                - 8208967d40e9980bab6d12367dc88e0b
              source: 0
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are successfully sent.
                  fail:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are not sent successfully.
                x-apifox-orders:
                  - success
                  - fail
                required:
                  - success
                  - fail
              example:
                success:
                  - 4PBXun3mQoZGnKdLKoDtBA==
                  - 4PBXun32QoZGnKdLKoDtBA==
                fail:
                  - 4PBXun3mQoZGnKdLKoDtBA==
                  - 4PBXun32QoZGnKdLKoDtBA==
          headers: {}
          x-apifox-name: OK
      security: []
      x-apifox-folder: VNNOX/Real-Time Control
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-180498657-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Screen Status

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/real-time-control/screen-status:
    post:
      summary: Screen Status
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for controlling the screen status (black
        screen/normal).

        2. Sub-accounts can only manage data within their specific workgroup and
        its sub-workgroups.

        3. Advanced interface.

        :::
      tags:
        - VNNOX/Real-Time Control
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                  description: >-
                    A collection of player IDs to be handled. At most 100 player
                    IDs can be handled simultaneously.
                status:
                  type: string
                  description: Status，OPEN-Normal，CLOSE-Black screen.
              x-apifox-orders:
                - playerIds
                - status
              required:
                - playerIds
                - status
            example:
              playerIds:
                - 553cbfe2ff4ad2e0d6bd89bb2c4e85e2
              status: OPEN
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are successfully sent.
                  fail:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are not sent successfully.
                required:
                  - success
                  - fail
                x-apifox-orders:
                  - success
                  - fail
              example:
                success:
                  - 553cbfe2ff4ad2e0d6bd89bb2c4e85e2
                fail: []
          headers: {}
          x-apifox-name: OK
      security: []
      x-apifox-folder: VNNOX/Real-Time Control
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-180498658-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```

# Restart Players

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/real-time-control/reboot:
    post:
      summary: Restart Players
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for restarting players.

        2. Sub-accounts can only manage data within their specific workgroup and
        its sub-workgroups.

        3. Advanced interface.

        :::
      tags:
        - VNNOX/Real-Time Control
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                  description: >-
                    A collection of player IDs to be handled. At most 100 player
                    IDs can be handled simultaneously.
              x-apifox-orders:
                - playerIds
              required:
                - playerIds
            example:
              playerIds:
                - df6c02352e4fd3cd5bc664fcdaef29c9
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are successfully sent.
                  fail:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are not sent successfully.
                required:
                  - success
                  - fail
                x-apifox-orders:
                  - success
                  - fail
              example:
                success:
                  - df6c02352e4fd3cd5bc664fcdaef29c9
                fail: []
          headers: {}
          x-apifox-name: OK
      security: []
      x-apifox-folder: VNNOX/Real-Time Control
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-180498659-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Screen Power

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/real-time-control/power:
    post:
      summary: Screen Power
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for controlling the screen power. (You need to
        use LCT or ViPlex Express to configure the power control device as
        “Screen Power”.)

        2. Sub-accounts can only manage data within their specific workgroup and
        its sub-workgroups.

        3. Advanced interface.

        :::
      tags:
        - VNNOX/Real-Time Control
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                  description: >-
                    A collection of player IDs to be handled. At most 100 player
                    IDs can be handled simultaneously.
                option:
                  type: integer
                  description: Control panel power status, 0-off, 1-on.
              x-apifox-orders:
                - playerIds
                - option
              required:
                - playerIds
                - option
            example:
              playerIds:
                - 4PBXun3mQoZGnKdLKoDtBA==
                - 4PBXun32QoZGnKdLKoDtBA==
              option: 1
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are successfully sent.
                  fail:
                    type: array
                    items:
                      type: string
                    description: A collection of player IDs that are not sent successfully.
                x-apifox-orders:
                  - success
                  - fail
                required:
                  - success
                  - fail
              example:
                success:
                  - 4PBXun3mQoZGnKdLKoDtBA==
                  - 4PBXun32QoZGnKdLKoDtBA==
                fail:
                  - 4PBXun3mQoZGnKdLKoDtBA==
                  - 4PBXun32QoZGnKdLKoDtBA==
          headers: {}
          x-apifox-name: OK
      security: []
      x-apifox-folder: VNNOX/Real-Time Control
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-180498660-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Scheduled Screen Status

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/scheduled-control/screen-status:
    post:
      summary: Scheduled Screen Status
      deprecated: false
      description: >-
        :::tip

        1. This interface provides scheduled screen state switching (black
        screen | normal display) for media players, with batch support for up to
        100 players.

        2. Sub-accounts have permission to manage only players within their
        assigned workgroup and any nested sub-workgroups.

        3. Advanced interface.

        :::
      tags:
        - VNNOX/Scheduled Control
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                  description: >-
                    Array of player IDs to process. Maximum batch size: 100
                    players.
                schedules:
                  type: array
                  items:
                    type: object
                    properties:
                      startDate:
                        type: string
                        description: >-
                          The start date of the scheduled plan, in the format of
                          YYYY-MM-DD (e.g. 2023-01-01)
                      endDate:
                        type: string
                        description: >-
                          The end date of the scheduled plan, in the format of
                          YYYY-MM-DD (e.g. 2023-01-01)
                      weekDays:
                        type: array
                        items:
                          type: integer
                        description: >-
                          Scheduled week configuration, effective elements range
                          0-6: 0-Sunday, 1-Monday, 2-Tuesday, 3-Wednesday,
                          4-Thursday, 5-Friday, 6-Saturday. If any element
                          exists, the schedule takes effect on the day, and the
                          default or empty collection indicates that it is
                          executed every day within the validity period. And if
                          the parameter is not provided,  it indicates that the
                          plan will executed daily.
                      execTime:
                        type: string
                        description: >-
                          The scheduled execution time is in 24-hour HH:MM:SS
                          format (for example, 21:00:00). Note: The player
                          triggers the execution plan based on the player local
                          time.
                      status:
                        type: string
                        description: >-
                          Player screen control status: OPEN (normal display),
                          CLOSE (screen display off)
                    x-apifox-refs: {}
                    x-apifox-orders:
                      - startDate
                      - endDate
                      - weekDays
                      - execTime
                      - status
                    required:
                      - startDate
                      - endDate
                      - execTime
                      - status
                  description: >-
                    A collection of time scheduling plans. If an empty array is
                    passed, all set time schedules will be cleared.
              x-apifox-orders:
                - playerIds
                - schedules
              required:
                - playerIds
                - schedules
            example:
              playerIds:
                - f14cb76a01320c2c4fba81bc0cb4b3af
                - 85dacf69cb2c57bb508a6d126d189383
                - 8921b722dc7f6b1b1e20dafcf553a554
                - 6c09ee7576f9ec298be5e20e21569adb
                - f4db107e317e841585bbd7a67573885e
                - 9b9ce094e2b9d70576460bd4c475748d
              schedules:
                - startDate: '2025-01-01'
                  endDate: '2025-12-31'
                  weekDays: []
                  execTime: '06:30:00'
                  status: OPEN
                - startDate: '2025-01-01'
                  endDate: '2025-12-31'
                  weekDays: []
                  execTime: '21:30:00'
                  status: CLOSE
                - startDate: '2025-01-01'
                  endDate: '2025-12-31'
                  weekDays:
                    - 1
                    - 2
                    - 3
                    - 4
                    - 5
                  execTime: '09:00:00'
                  status: CLOSE
                - startDate: '2025-01-01'
                  endDate: '2025-12-31'
                  weekDays:
                    - 1
                    - 2
                    - 3
                    - 4
                    - 5
                  execTime: '17:00:00'
                  status: OPEN
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                    description: Array of success player IDs
                  fail:
                    type: array
                    items:
                      type: string
                    description: Array of fail player IDs
                required:
                  - success
                  - fail
                x-apifox-orders:
                  - success
                  - fail
              example:
                success:
                  - 8f29699ae6db7c584b60fa345c984a0e
                  - f14cb76a01320c2c4fba81bc0cb4b3af
                  - 85dacf69cb2c57bb508a6d126d189383
                  - 8921b722dc7f6b1b1e20dafcf553a554
                  - 6c09ee7576f9ec298be5e20e21569adb
                  - f4db107e317e841585bbd7a67573885e
                  - 9b9ce094e2b9d70576460bd4c475748d
                fail: []
          headers: {}
          x-apifox-name: Success
      security: []
      x-apifox-folder: VNNOX/Scheduled Control
      x-apifox-status: testing
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-285406451-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Scheduled Restart Players

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/scheduled-control/reboot:
    post:
      summary: Scheduled Restart Players
      deprecated: false
      description: >-
        :::tip

        1. This interface provides scheduled restart functionality for media
        players, with batch support for up to 100 players.

        2. Sub-accounts have permission to manage only players within their
        assigned workgroup and any nested sub-workgroups.

        3. Advanced interface.

        :::
      tags:
        - VNNOX/Scheduled Control
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                  description: >-
                    Array of player IDs to process. Maximum batch size: 100
                    players.
                schedules:
                  type: array
                  items:
                    type: object
                    properties:
                      startDate:
                        type: string
                        description: >-
                          The start date of the scheduled plan, in the format of
                          YYYY-MM-DD (e.g. 2023-01-01)
                      endDate:
                        type: string
                        description: >-
                          The end date of the scheduled plan, in the format of
                          YYYY-MM-DD (e.g. 2023-01-01)
                      weekDays:
                        type: array
                        items:
                          type: integer
                        description: >-
                          Scheduled week configuration, effective elements range
                          0-6: 0-Sunday, 1-Monday, 2-Tuesday, 3-Wednesday,
                          4-Thursday, 5-Friday, 6-Saturday. If any element
                          exists, the schedule takes effect on the day, and the
                          default or empty collection indicates that it is
                          executed every day within the validity period. And if
                          the parameter is not provided,  it indicates that the
                          plan will executed daily.
                      execTime:
                        type: string
                        description: >-
                          The scheduled execution time is in 24-hour HH:MM:SS
                          format (for example, 21:00:00). Note: The player
                          triggers the execution plan based on the player local
                          time.
                    x-apifox-refs: {}
                    x-apifox-orders:
                      - startDate
                      - endDate
                      - weekDays
                      - execTime
                    required:
                      - startDate
                      - endDate
                      - execTime
                  description: >-
                    A collection of time scheduling plans. If an empty array is
                    passed, all set time schedules will be cleared.
              x-apifox-orders:
                - playerIds
                - schedules
              required:
                - playerIds
                - schedules
            example:
              playerIds:
                - f14cb76a01320c2c4fba81bc0cb4b3af
                - 85dacf69cb2c57bb508a6d126d189383
                - 8921b722dc7f6b1b1e20dafcf553a554
                - 6c09ee7576f9ec298be5e20e21569adb
                - f4db107e317e841585bbd7a67573885e
                - 9b9ce094e2b9d70576460bd4c475748d
              schedules:
                - startDate: '2025-01-01'
                  endDate: '2025-12-31'
                  weekDays:
                    - 1
                    - 2
                    - 3
                    - 4
                  execTime: '22:00:00'
                - startDate: '2025-01-01'
                  endDate: '2025-12-31'
                  weekDays:
                    - 0
                    - 5
                    - 6
                  execTime: '23:30:00'
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                  fail:
                    type: array
                    items:
                      type: string
                required:
                  - success
                  - fail
                x-apifox-orders:
                  - success
                  - fail
              example:
                success:
                  - 8f29699ae6db7c584b60fa345c984a0e
                  - f14cb76a01320c2c4fba81bc0cb4b3af
                  - 85dacf69cb2c57bb508a6d126d189383
                  - 8921b722dc7f6b1b1e20dafcf553a554
                  - 6c09ee7576f9ec298be5e20e21569adb
                  - f4db107e317e841585bbd7a67573885e
                  - 9b9ce094e2b9d70576460bd4c475748d
                fail: []
          headers: {}
          x-apifox-name: Success
      security: []
      x-apifox-folder: VNNOX/Scheduled Control
      x-apifox-status: testing
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-285484784-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Scheduled Volume Adjustment

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/scheduled-control/volume:
    post:
      summary: Scheduled Volume Adjustment
      deprecated: false
      description: >-
        :::tip

        1. This interface provides scheduled volume control for media players,
        with batch support for up to 100 players.

        2. Sub-accounts have permission to manage only players within their
        assigned workgroup and any nested sub-workgroups.

        3. Advanced interface.

        :::
      tags:
        - VNNOX/Scheduled Control
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                  description: >-
                    Array of player IDs to process. Maximum batch size: 100
                    players.
                schedules:
                  type: array
                  items:
                    type: object
                    properties:
                      startDate:
                        type: string
                        description: >-
                          The start date of the scheduled plan, in the format of
                          YYYY-MM-DD (e.g. 2023-01-01)
                      endDate:
                        type: string
                        description: >-
                          The end date of the scheduled plan, in the format of
                          YYYY-MM-DD (e.g. 2023-01-01)
                      weekDays:
                        type: array
                        items:
                          type: integer
                        description: >-
                          Scheduled week configuration, effective elements range
                          0-6: 0-Sunday, 1-Monday, 2-Tuesday, 3-Wednesday,
                          4-Thursday, 5-Friday, 6-Saturday. If any element
                          exists, the schedule takes effect on the day, and the
                          default or empty collection indicates that it is
                          executed every day within the validity period. And if
                          the parameter is not provided,  it indicates that the
                          plan will executed daily.
                      execTime:
                        type: string
                        description: >-
                          The scheduled execution time is in 24-hour HH:MM:SS
                          format (for example, 21:00:00). Note: The player
                          triggers the execution plan based on the player local
                          time.
                      value:
                        type: integer
                        description: 'volume value, valid range: 0-100'
                    x-apifox-refs: {}
                    x-apifox-orders:
                      - startDate
                      - endDate
                      - weekDays
                      - execTime
                      - value
                    required:
                      - startDate
                      - endDate
                      - execTime
                      - value
                  description: >-
                    A collection of time scheduling plans. If an empty array is
                    passed, all set time schedules will be cleared.
              x-apifox-orders:
                - playerIds
                - schedules
              required:
                - playerIds
                - schedules
            example:
              playerIds:
                - f14cb76a01320c2c4fba81bc0cb4b3af
                - 85dacf69cb2c57bb508a6d126d189383
                - 8921b722dc7f6b1b1e20dafcf553a554
                - 6c09ee7576f9ec298be5e20e21569adb
                - f4db107e317e841585bbd7a67573885e
                - 9b9ce094e2b9d70576460bd4c475748d
              schedules:
                - startDate: '2025-01-01'
                  endDate: '2025-12-31'
                  execTime: '08:30:00'
                  value: 55
                - startDate: '2025-01-01'
                  endDate: '2025-12-31'
                  execTime: '22:00:00'
                  value: 40
                - startDate: '2025-01-01'
                  endDate: '2025-12-31'
                  weekDays:
                    - 0
                    - 5
                    - 6
                  execTime: '17:00:00'
                  value: 70
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                  fail:
                    type: array
                    items:
                      type: string
                required:
                  - success
                  - fail
                x-apifox-orders:
                  - success
                  - fail
              example:
                success:
                  - 8f29699ae6db7c584b60fa345c984a0e
                  - f14cb76a01320c2c4fba81bc0cb4b3af
                  - 85dacf69cb2c57bb508a6d126d189383
                  - 8921b722dc7f6b1b1e20dafcf553a554
                  - 6c09ee7576f9ec298be5e20e21569adb
                  - f4db107e317e841585bbd7a67573885e
                  - 9b9ce094e2b9d70576460bd4c475748d
                fail: []
          headers: {}
          x-apifox-name: Success
      security: []
      x-apifox-folder: VNNOX/Scheduled Control
      x-apifox-status: testing
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-285492978-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```



# Scheduled Brightness Adjustment

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/scheduled-control/brightness:
    post:
      summary: Scheduled Brightness Adjustment
      deprecated: false
      description: >-
        :::tip

        1. This interface provides scheduled brightness control for media
        players, with batch support for up to 100 players.

        2. Sub-accounts have permission to manage only players within their
        assigned workgroup and any nested sub-workgroups.

        3. Advanced interface.

        :::
      tags:
        - VNNOX/Scheduled Control
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                  description: >-
                    Array of player IDs to process. Maximum batch size: 100
                    players.
                schedules:
                  type: array
                  items:
                    type: object
                    properties:
                      startDate:
                        type: string
                        description: >-
                          The start date of the scheduled plan, in the format of
                          YYYY-MM-DD (e.g. 2023-01-01)
                      endDate:
                        type: string
                        description: >-
                          The end date of the scheduled plan, in the format of
                          YYYY-MM-DD (e.g. 2023-01-01)
                      weekDays:
                        type: array
                        items:
                          type: integer
                        description: >-
                          Scheduled week configuration, effective elements range
                          0-6: 0-Sunday, 1-Monday, 2-Tuesday, 3-Wednesday,
                          4-Thursday, 5-Friday, 6-Saturday. If any element
                          exists, the schedule takes effect on the day, and the
                          default or empty collection indicates that it is
                          executed every day within the validity period. And if
                          the parameter is not provided,  it indicates that the
                          plan will executed daily.
                      execTime:
                        type: string
                        description: >-
                          The scheduled execution time is in 24-hour HH:MM:SS
                          format (for example, 21:00:00). Note: The player
                          triggers the execution plan based on the player local
                          time.
                      value:
                        type: integer
                        description: >-
                          Player brightness percentage, valid range: 0-100,  If
                          the control type is set to 0, then this parameter is
                          required.
                      type:
                        type: integer
                        description: >-
                          Control type, 0 for fix control, 1 for auto smart
                          contorl.
                    x-apifox-refs: {}
                    x-apifox-orders:
                      - startDate
                      - endDate
                      - weekDays
                      - execTime
                      - type
                      - value
                    required:
                      - startDate
                      - endDate
                      - execTime
                      - type
                  description: >-
                    A collection of time scheduling plans. If an empty array is
                    passed, all set time schedules will be cleared.
                autoProfile:
                  type: object
                  properties:
                    failValue:
                      type: integer
                      description: >-
                        The default fallback brightness pecentage value, the
                        valid range is: 0-100. When the environmental
                        brightness  failes to be obtained, the fallback value is
                        used. 
                    segments:
                      type: array
                      items:
                        type: object
                        properties:
                          id:
                            type: integer
                            description: >-
                              Segment ID, starts from 0 and needs to be
                              continuous
                          environmentBrightness:
                            type: string
                            description: >-
                              Environmental brightness value, unit lumens,
                              effective range: 0-65534. Typical environment
                              brightness is 500-2000 indoors, 2000-10000
                              outdoors.
                          screenBrightness:
                            type: string
                            description: >-
                              Screen target brightness percentage value,  the
                              valid range is: 0-100.
                        x-apifox-orders:
                          - id
                          - environmentBrightness
                          - screenBrightness
                        required:
                          - id
                          - environmentBrightness
                          - screenBrightness
                      description: >-
                        An array of auto-brightness segments that maps
                        environmental brightness to screen target brightness
                        (X-Y relationship).
                  x-apifox-orders:
                    - failValue
                    - segments
                  description: >-
                    Auto brightness configuration profile, This parameter is
                    required unless all scheduled plans are fixed (type=0).
                  required:
                    - failValue
                    - segments
              x-apifox-orders:
                - playerIds
                - schedules
                - autoProfile
              required:
                - playerIds
                - schedules
            example:
              playerIds:
                - 8f29699ae6db7c584b60fa345c984a0e
                - f14cb76a01320c2c4fba81bc0cb4b3af
                - 85dacf69cb2c57bb508a6d126d189383
                - 8921b722dc7f6b1b1e20dafcf553a554
                - 6c09ee7576f9ec298be5e20e21569adb
                - f4db107e317e841585bbd7a67573885e
                - 9b9ce094e2b9d70576460bd4c475748d
              schedules:
                - startDate: '2025-01-01'
                  endDate: '2025-12-31'
                  weekDays:
                    - 1
                    - 2
                    - 3
                    - 4
                    - 5
                  execTime: '07:30:00'
                  type: 2
                - startDate: '2025-01-01'
                  endDate: '2025-12-31'
                  weekDays:
                    - 1
                    - 2
                    - 3
                    - 4
                    - 5
                  execTime: '19:00:00'
                  type: 1
                  value: 40
              autoProfile:
                failValue: 50
                segments:
                  - environmentBrightness: 500
                    screenBrightness: 20
                    id: 0
                  - environmentBrightness: 1200
                    screenBrightness: 30
                    id: 1
                  - environmentBrightness: 2000
                    screenBrightness: 40
                    id: 2
                  - environmentBrightness: 5000
                    screenBrightness: 60
                    id: 3
                  - environmentBrightness: 8000
                    screenBrightness: 80
                    id: 4
                  - environmentBrightness: 12000
                    screenBrightness: 90
                    id: 5
                  - environmentBrightness: 30000
                    screenBrightness: 100
                    id: 6
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                  fail:
                    type: array
                    items:
                      type: string
                required:
                  - success
                  - fail
                x-apifox-orders:
                  - success
                  - fail
              example:
                success:
                  - 8f29699ae6db7c584b60fa345c984a0e
                  - f14cb76a01320c2c4fba81bc0cb4b3af
                  - 85dacf69cb2c57bb508a6d126d189383
                  - 8921b722dc7f6b1b1e20dafcf553a554
                  - 6c09ee7576f9ec298be5e20e21569adb
                fail:
                  - f4db107e317e841585bbd7a67573885e
                  - 9b9ce094e2b9d70576460bd4c475748d
          headers: {}
          x-apifox-name: Success
      security: []
      x-apifox-folder: VNNOX/Scheduled Control
      x-apifox-status: testing
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-285501603-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Scheduled Video Source Switching

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/player/scheduled-control/video-source:
    post:
      summary: Scheduled Video Source Switching
      deprecated: false
      description: >-
        :::tip

        1. This interface provides scheduled video source switching, with batch
        support for up to 100 players.

        2. Sub-accounts have permission to manage only players within their
        assigned workgroup and any nested sub-workgroups.

        3. Advanced interface.

        :::
      tags:
        - VNNOX/Scheduled Control
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                  description: >-
                    Array of player IDs to process. Maximum batch size: 100
                    players.
                schedules:
                  type: array
                  items:
                    type: object
                    properties:
                      startDate:
                        type: string
                        description: >-
                          The start date of the scheduled plan, in the format of
                          YYYY-MM-DD (e.g. 2023-01-01)
                      endDate:
                        type: string
                        description: >-
                          The end date of the scheduled plan, in the format of
                          YYYY-MM-DD (e.g. 2023-01-01)
                      weekDays:
                        type: array
                        items:
                          type: integer
                        description: >-
                          Scheduled week configuration, effective elements range
                          0-6: 0-Sunday, 1-Monday, 2-Tuesday, 3-Wednesday,
                          4-Thursday, 5-Friday, 6-Saturday. If any element
                          exists, the schedule takes effect on the day, and the
                          default or empty collection indicates that it is
                          executed every day within the validity period. And if
                          the parameter is not provided,  it indicates that the
                          plan will executed daily.
                      execTime:
                        type: string
                        description: >-
                          The scheduled execution time is in 24-hour HH:MM:SS
                          format (for example, 21:00:00). Note: The player
                          triggers the execution plan based on the player local
                          time.
                      source:
                        type: integer
                        description: Video source type, 0-internal, 1-external.
                    x-apifox-refs: {}
                    x-apifox-orders:
                      - startDate
                      - endDate
                      - weekDays
                      - execTime
                      - source
                    required:
                      - startDate
                      - endDate
                      - execTime
                      - source
                  description: >-
                    A collection of time scheduling plans. If an empty array is
                    passed, all set time schedules will be cleared.
              x-apifox-orders:
                - playerIds
                - schedules
              required:
                - playerIds
                - schedules
            example:
              playerIds:
                - f14cb76a01320c2c4fba81bc0cb4b3af
                - 85dacf69cb2c57bb508a6d126d189383
                - 8921b722dc7f6b1b1e20dafcf553a554
                - 6c09ee7576f9ec298be5e20e21569adb
                - f4db107e317e841585bbd7a67573885e
                - 9b9ce094e2b9d70576460bd4c475748d
              schedules:
                - startDate: '2025-01-01'
                  endDate: '2025-12-31'
                  execTime: '08:30:00'
                  weekDays:
                    - 0
                    - 6
                  source: 0
                - startDate: '2025-01-01'
                  endDate: '2025-12-31'
                  weekDays:
                    - 1
                    - 2
                    - 3
                    - 4
                    - 5
                  execTime: '08:30:00'
                  source: 1
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                  fail:
                    type: array
                    items:
                      type: string
                required:
                  - success
                  - fail
                x-apifox-orders:
                  - success
                  - fail
              example:
                success:
                  - 8f29699ae6db7c584b60fa345c984a0e
                  - f14cb76a01320c2c4fba81bc0cb4b3af
                  - 85dacf69cb2c57bb508a6d126d189383
                  - 8921b722dc7f6b1b1e20dafcf553a554
                  - 6c09ee7576f9ec298be5e20e21569adb
                  - f4db107e317e841585bbd7a67573885e
                  - 9b9ce094e2b9d70576460bd4c475748d
                fail: []
          headers: {}
          x-apifox-name: Success
      security: []
      x-apifox-folder: VNNOX/Scheduled Control
      x-apifox-status: testing
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-285704459-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```

# Batch Searching for Play Log Overviews

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/logs/play-logs/batch-summary:
    post:
      summary: Batch Searching for Play Log Overviews
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for batch obtaining the play log overviews of
        players.

        2. An organization user have only one play log search task running at a
        time and must await the completion of the previous task before
        submitting a new one.

        3. The logs will be stored in our cloud space in the form of an Excel
        file and will be sent to the customer as a link. Please download and use
        it as needed.

        4. The logs within the past 3 months can be found, containing the logs
        of the start date, The logs within the past 30 consecutive days at most
        can be found. The logs only before the end date can be found.

        5. The Excel file can be retained in our cloud space for 7 days, and the
        file will be automatically deleted after 7 days. Please complete the
        download and transfer within 7 days.

        6. Advanced interface.

        :::
      tags:
        - VNNOX/Logs/Play Logs
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                  description: >-
                    A collection of player IDs to be handled. At most 100 player
                    can be handled simultaneously.
                startDate:
                  type: string
                  description: >-
                    Start date, in a format such as 2020-05-06 (The logs within
                    the past 3 months can be found, containing the logs of the
                    start date.)
                endDate:
                  type: string
                  description: >-
                    End date, in a format such as 2020-05-12 (The logs within
                    the past 30 consecutive days at most can be found. The logs
                    only before the end date can be found.)
                noticeUrl:
                  type: string
                  description: >-
                    After the log is processed successfully, the system will
                    call back this address and send it to the customer in the
                    form of [POST-JSON]. The response time of the interface
                    cannot exceed 3s. The interface of the customer must return
                    the "ok" string, otherwise the system will retry.
              required:
                - playerIds
                - startDate
                - endDate
                - noticeUrl
              x-apifox-orders:
                - playerIds
                - startDate
                - endDate
                - noticeUrl
            example:
              playerIds:
                - df6c02352e4fd3cd5bc664fcdaef29c9
              startDate: '2024-06-01'
              endDate: '2024-06-19'
              noticeUrl: http://sit-api.vnnox.com/test/noticeScreenShotUrl
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  taskId:
                    type: string
                    description: No. of the current search task
                required:
                  - taskId
                x-apifox-orders:
                  - taskId
              example:
                taskId: '5'
          headers: {}
          x-apifox-name: 成功
        x-200:Call-back Parameters for Notifying Users of Play Log Information:
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  ossUrl:
                    type: string
                    description: Download link of the play log Excel file
                  totalCount:
                    type: string
                    description: Total number of log records searched
                  taskId:
                    type: string
                    description: Search task ID
                  status:
                    type: integer
                    description: >-
                      Execution status of the current task: 2-successful,
                      3-failed
                required:
                  - ossUrl
                  - totalCount
                  - taskId
                  - status
                x-apifox-orders:
                  - ossUrl
                  - totalCount
                  - taskId
                  - status
              example:
                ossUrl: >-
                  'https://download.vnnox.com/api/vnnox/zh-playlog-detail-empty.xlsx
                totalCount: '21'
                taskId: '5'
                status: 2
          headers: {}
          x-apifox-name: Call-back Parameters for Notifying Users of Play Log Information
      security: []
      x-apifox-folder: VNNOX/Logs/Play Logs
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-188119325-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Batch Searching for Play Log Details

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/logs/play-logs/batch-detail:
    post:
      summary: Batch Searching for Play Log Details
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for batch obtaining the play log details of
        players.

        2. An organization user have only one play log search task running at a
        time and must await the completion of the previous task before
        submitting a new one.

        3. The logs will be stored in our cloud space in the form of an Excel
        file and will be sent to the customer as a link. Please download and use
        it as needed.

        4. The logs within the past 3 months can be found, containing the logs
        of the start date, The logs within the past 7 consecutive days at most
        can be found. The logs only before the end date can be found.

        5. The Excel file can be retained in our cloud space for 7 days, and the
        file will be automatically deleted after 7 days. Please complete the
        download and transfer within 7 days.

        6. Advanced interface.

        :::
      tags:
        - VNNOX/Logs/Play Logs
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                  description: >-
                    A collection of player IDs to be handled. At most 100 player
                    can be handled simultaneously.
                startDate:
                  type: string
                  description: >-
                    Start date, in a format such as 2020-05-06 (The logs within
                    the past 3 months can be found, containing the logs of the
                    start date.)
                endDate:
                  type: string
                  description: >-
                    End date, in a format such as 2020-05-12 (The logs within
                    the past 7 consecutive days at most can be found. The logs
                    only before the end date can be found.)
                noticeUrl:
                  type: string
                  description: >-
                    After the log is processed successfully, the system will
                    call back this address and send it to the customer in the
                    form of [POST-JSON]. The response time of the interface
                    cannot exceed 3s. The interface of the customer must return
                    the "ok" string, otherwise the system will retry.
              required:
                - playerIds
                - startDate
                - endDate
                - noticeUrl
              x-apifox-orders:
                - playerIds
                - startDate
                - endDate
                - noticeUrl
            example:
              playerIds:
                - df6c02352e4fd3cd5bc664fcdaef29c9
              startDate: '2024-06-01'
              endDate: '2024-06-19'
              noticeUrl: http://sit-api.vnnox.com/test/noticeScreenShotUrl
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  taskId:
                    type: string
                    description: No. of the current search task
                required:
                  - taskId
                x-apifox-orders:
                  - taskId
              example:
                taskId: '5'
          headers: {}
          x-apifox-name: 成功
        x-200:Call-back Parameters for Notifying Users of Play Log Information:
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  ossUrl:
                    type: string
                    description: Download link of the play log Excel file
                  totalCount:
                    type: string
                    description: Total number of log records searched
                  taskId:
                    type: string
                    description: Search task ID
                  status:
                    type: integer
                    description: >-
                      Execution status of the current task: 2-successful,
                      3-failed
                required:
                  - ossUrl
                  - totalCount
                  - taskId
                  - status
                x-apifox-orders:
                  - ossUrl
                  - totalCount
                  - taskId
                  - status
              example:
                ossUrl: >-
                  'https://download.vnnox.com/api/vnnox/zh-playlog-detail-empty.xlsx
                totalCount: '21'
                taskId: '5'
                status: 2
          headers: {}
          x-apifox-name: Call-back Parameters for Notifying Users of Play Log Information
      security: []
      x-apifox-folder: VNNOX/Logs/Play Logs
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-189311783-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Searching for the Play Log Detail of a Single Player

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/logs/play-logs/single-detail:
    get:
      summary: Searching for the Play Log Detail of a Single Player
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for obtaining the play log detail of a single
        player.

        2. An organization user have only one play log search task running at a
        time and must await the completion of the previous task before
        submitting a new one.

        3. The logs will be stored in our cloud space in the form of an Excel
        file and will be sent to the customer as a link. Please download and use
        it as needed.

        4. The logs within the past 3 months can be found, containing the logs
        of the start date, The logs within the past 30 consecutive days at most
        can be found. The logs only before the end date can be found.

        5. The Excel file can be retained in our cloud space for 7 days, and the
        file will be automatically deleted after 7 days. Please complete the
        download and transfer within 7 days.

        6. Advanced interface.

        :::
      tags:
        - VNNOX/Logs/Play Logs
      parameters:
        - name: playerId
          in: query
          description: Player ID to be handled.
          required: true
          schema:
            type: string
        - name: noticeUrl
          in: query
          description: >-
            After the log is processed successfully, the system will call back
            this address and send it to the customer in the form of [POST-JSON].
            The response time of the interface cannot exceed 3s. The interface
            of the customer must return the "ok" string, otherwise the system
            will retry.
          required: true
          schema:
            type: string
        - name: startDate
          in: query
          description: >-
            Start date, in a format such as 2020-05-06 (The logs within the past
            3 months can be found, containing the logs of the start date.)
          required: true
          schema:
            type: string
        - name: endDate
          in: query
          description: >-
            End date, in a format such as 2020-05-12 (The logs within the past
            30 consecutive days at most can be found. The logs only before the
            end date can be found.)
          required: true
          schema:
            type: string
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  taskId:
                    type: string
                    description: No. of the current search task
                required:
                  - taskId
                x-apifox-orders:
                  - taskId
              example:
                taskId: '5'
          headers: {}
          x-apifox-name: 成功
        x-200:Call-back Parameters for Notifying Users of Play Log Information:
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  ossUrl:
                    type: string
                    description: Download link of the play log Excel file
                  totalCount:
                    type: string
                    description: Total number of log records searched
                  taskId:
                    type: string
                    description: Search task ID
                  status:
                    type: integer
                    description: >-
                      Execution status of the current task: 2-successful,
                      3-failed
                required:
                  - ossUrl
                  - totalCount
                  - taskId
                  - status
                x-apifox-orders:
                  - ossUrl
                  - totalCount
                  - taskId
                  - status
              example:
                ossUrl: >-
                  https://novacloud-dev.oss-cn-hangzhou.aliyuncs.com/novaplaylog/zhouchen/2024-06-21/5_20240621172630.xlsx
                totalCount: '21'
                taskId: '5'
                status: 2
          headers: {}
          x-apifox-name: Call-back Parameters for Notifying Users of Play Log Information
      security: []
      x-apifox-folder: VNNOX/Logs/Play Logs
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-188120581-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Searching for the Play Log Overview of a Single Player

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/logs/play-logs/single-summary:
    get:
      summary: Searching for the Play Log Overview of a Single Player
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for batch obtaining the play log overview of a
        single player.

        2. An organization user have only one play log search task running at a
        time and must await the completion of the previous task before
        submitting a new one.

        3. The logs will be stored in our cloud space in the form of an Excel
        file and will be sent to the customer as a link. Please download and use
        it as needed.

        4. The logs within the past 3 months can be found, containing the logs
        of the start date, The logs within the past 30 consecutive days at most
        can be found. The logs only before the end date can be found.

        5. The Excel file can be retained in our cloud space for 7 days, and the
        file will be automatically deleted after 7 days. Please complete the
        download and transfer within 7 days.

        6. Advanced interface.

        :::
      tags:
        - VNNOX/Logs/Play Logs
      parameters:
        - name: playerId
          in: query
          description: Player ID to be handled.
          required: true
          schema:
            type: string
        - name: noticeUrl
          in: query
          description: >-
            After the log is processed successfully, the system will call back
            this address and send it to the customer in the form of [POST-JSON].
            The response time of the interface cannot exceed 3s. The interface
            of the customer must return the "ok" string, otherwise the system
            will retry.
          required: true
          schema:
            type: string
        - name: startDate
          in: query
          description: >-
            Start date, in a format such as 2020-05-06 (The logs within the past
            3 months can be found, containing the logs of the start date.)
          required: true
          schema:
            type: string
        - name: endDate
          in: query
          description: >-
            End date, in a format such as 2020-05-12 (The logs within the past
            30 consecutive days at most can be found. The logs only before the
            end date can be found.)
          required: true
          schema:
            type: string
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  taskId:
                    type: string
                    description: No. of the current search task
                required:
                  - taskId
                x-apifox-orders:
                  - taskId
              example:
                taskId: '5'
          headers: {}
          x-apifox-name: 成功
        x-200:Call-back Parameters for Notifying Users of Play Log Information:
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  ossUrl:
                    type: string
                    description: Download link of the play log Excel file
                  totalCount:
                    type: string
                    description: Total number of log records searched
                  taskId:
                    type: string
                    description: Search task ID
                  status:
                    type: integer
                    description: >-
                      Execution status of the current task: 2-successful,
                      3-failed
                required:
                  - ossUrl
                  - totalCount
                  - taskId
                  - status
                x-apifox-orders:
                  - ossUrl
                  - totalCount
                  - taskId
                  - status
              example:
                ossUrl: >-
                  https://novacloud-dev.oss-cn-hangzhou.aliyuncs.com/novaplaylog/zhouchen/2024-06-21/5_20240621172630.xlsx
                totalCount: '21'
                taskId: '5'
                status: 2
          headers: {}
          x-apifox-name: Call-back Parameters for Notifying Users of Play Log Information
      security: []
      x-apifox-folder: VNNOX/Logs/Play Logs
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-189316709-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```



# Obtaining Control Command Execution Logs

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/logs/remote-control:
    get:
      summary: Obtaining Control Command Execution Logs
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for obtaining the command execution history of
        a player.

        2. Sub-accounts can only manage data within their specific workgroup and
        its sub-workgroups.

        3. Advanced interface.

        :::
      tags:
        - VNNOX/Logs
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                count:
                  type: integer
                  description: >-
                    Items of each type of historical data that can be read each
                    time, 20 by default, range: 1~100
                start:
                  type: integer
                  description: >-
                    From which item to start reading each type of historical
                    data, 0 by default
                taskType:
                  type: integer
                  description: >-
                    1: Restart, 2: Screen status control, 4: Video source
                    switching, 3: Synchronous playback, 5: Upgrade, 6:
                    Screenshot, 7: Time zone, 8: Power control, 9: Volume
                    adjustment 10: Brightness adjustment
                playerId:
                  type: string
                  description: >-
                    Player IDs to be handled. Only a single player can be
                    queried.
              x-apifox-orders:
                - playerId
                - count
                - start
                - taskType
              required:
                - count
                - start
                - taskType
                - playerId
            example:
              playerId: df6c02352e4fd3cd5bc664fcdaef29c9
              count: 20
              start: 0
              taskType: 1
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  total:
                    type: integer
                    description: Total number of items of history
                  rows:
                    type: array
                    items:
                      type: object
                      properties:
                        status:
                          type: string
                          description: >-
                            Command execution result, 0: Unknown 1: Successful
                            2: Failed
                        executeTime:
                          type: string
                          description: Command execution time
                        type:
                          type: string
                          description: >-
                            Command type  reboot-Restart、openScreen-Turn on
                            screen、closeScreen-Turn off screen、openSyncPlay-Turn
                            on synchronous playback、closeSynsPlay-Turn off
                            synchronous playback、internal-Internal video
                            source、HDMI-External video source、osUpdate-OS
                            update、appUpdate-APP update、firmwareUpdate-Firmware
                            update、screenShot-Screenshot、getTimezone-Get time
                            zone、setTimezone-Set time zone、setVolume-Set time
                            zone、setBrightness-Set brightness
                        ratio:
                          type: integer
                          description: >-
                            Specified volume or brightness. This is not
                            available for other types of commands.
                      x-apifox-orders:
                        - status
                        - executeTime
                        - type
                        - ratio
                    description: Historical array
                required:
                  - total
                  - rows
                x-apifox-orders:
                  - total
                  - rows
              example:
                total: 3
                rows:
                  - status: 1
                    executeTime: '2024-07-02 04:55:48'
                    type: openScreen
                  - status: 1
                    executeTime: '2024-07-02 04:55:33'
                    type: openScreen
                  - status: 1
                    executeTime: '2024-07-02 04:55:16'
                    type: openScreen
          headers: {}
          x-apifox-name: OK
      security: []
      x-apifox-folder: VNNOX/Logs
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-186383461-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```

# Video Source Change Notifications

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/notification-hooks/video-source-updates:
    post:
      summary: Video Source Change Notifications
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for notifying customers of the internal and
        external source changes of players.

        2. Advanced interface.

        :::
      tags:
        - VNNOX/Notifications
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                  description: >-
                    Player IDs to be handled. Up to 100 players can be handled
                    at the smme time.
                noticeUrl:
                  type: string
                  description: Notification callback URL
              x-apifox-orders:
                - playerIds
                - noticeUrl
              required:
                - playerIds
                - noticeUrl
            example:
              playerIds:
                - 8208967d40e9980bab6d12367dc88e0b
              noticeUrl: https://aaa.com
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                    description: Player IDs sent successfully
                x-apifox-orders:
                  - success
                required:
                  - success
              example:
                success:
                  - 8208967d40e9980bab6d12367dc88e0b
          headers: {}
          x-apifox-name: 成功
        x-200:Video Source Change Notification Callback Parameters:
          description: ''
          content:
            application/json:
              schema:
                title: ''
                type: object
                properties:
                  playerId:
                    type: string
                    description: Corresponding player ID
                  videoSource:
                    type: string
                    description: Current video source 0-Internal source，1-External source
                  playerName:
                    type: string
                    description: Player name
                  updateTime:
                    type: string
                    description: Change time
                x-apifox-orders:
                  - playerId
                  - videoSource
                  - playerName
                  - updateTime
                required:
                  - playerId
                  - videoSource
                  - playerName
                  - updateTime
              example:
                playerId: 553cbfe2ff4ad2e0d6bd89bb2c4e85e2
                videoSource: '0'
                playerName: name111
                updateTime: '2022-05-12 09:08:59'
          headers: {}
          x-apifox-name: Video Source Change Notification Callback Parameters
      security: []
      x-apifox-folder: VNNOX/Notifications
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-186379152-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```



# Solution Change Notifications

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/notification-hooks/program-updates:
    post:
      summary: Solution Change Notifications
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for notifying customers of the internal and
        external source changes of players.

        2. Taurus V3.7.0 and later are supported.

        3. Advanced interface.

        :::
      tags:
        - VNNOX/Notifications
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                playerIds:
                  type: array
                  items:
                    type: string
                  description: >-
                    Player IDs to be handled. Up to 100 players can be handled
                    at the smme time.
                noticeUrl:
                  type: string
                  description: Notification callback URL
              x-apifox-orders:
                - playerIds
                - noticeUrl
              required:
                - playerIds
                - noticeUrl
            example:
              playerIds:
                - 8208967d40e9980bab6d12367dc88e0b
              noticeUrl: https://aaa.com
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: array
                    items:
                      type: string
                    description: Player IDs sent successfully
                x-apifox-orders:
                  - success
                required:
                  - success
              example:
                success:
                  - 8208967d40e9980bab6d12367dc88e0b
          headers: {}
          x-apifox-name: 成功
        x-200:Video Source Change Notification Callback Parameters:
          description: ''
          content:
            application/json:
              schema:
                title: ''
                type: object
                properties:
                  playerId:
                    type: string
                    description: Corresponding player ID
                  videoSource:
                    type: string
                    description: Current video source 0-Internal source，1-External source
                  playerName:
                    type: string
                    description: Player name
                  updateTime:
                    type: string
                    description: Change time
                x-apifox-orders:
                  - playerId
                  - videoSource
                  - playerName
                  - updateTime
                required:
                  - playerId
                  - videoSource
                  - playerName
                  - updateTime
              example:
                playerId: 553cbfe2ff4ad2e0d6bd89bb2c4e85e2
                videoSource: '0'
                playerName: name111
                updateTime: '2022-05-12 09:08:59'
          headers: {}
          x-apifox-name: Video Source Change Notification Callback Parameters
      security: []
      x-apifox-folder: VNNOX/Notifications
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-186380851-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Notes

1. Composition of monitoring interface location information: device type enumeration code, such as 020003000500
2. Composition of alarm interface location information: device type enumeration code + physical status enumeration code (last 4 digits), such as 01000200030005000000
3. Location information description

    a. Example: 01000200030005000000
        From the beginning, every four digits form a group.
        
        0100: 01 denotes the screen and 00 denotes the first screen (usually only one).
        
        0200: 02 denotes the sending card and 00 denotes the first sending card.
        
        0300: 03 denotes the Ethernet port and 00 denotes the first Ethernet port.
        
        0500: 05 denotes the receiving card and 00 denotes the first receiving card.
        
        0000: The first 00 denotes the voltage and the last 00 denotes the first voltage (usually only one) .
        
        
    b. Example: 01000200030005000D000E000100
        From the beginning, every four digits form a group.
        
        0100: 01 denotes the screen and 00 denotes the first screen (usually only one).
        
        0200: 02 denotes the sending card and 00 denotes the first sending card.
        
        0300: 03 denotes the Ethernet port and 00 denotes the first Ethernet port.
        
        0500: 05 denotes the receiving card and 00 denotes the first receiving card.
        
        0D00: 0D denotes the horizontal coordinate of the smart module and 00 denotes the horizontal coordinate is 1.
        
        0E00: 0E denotes the vertical coordinate of the smart module and 00 denotes that the vertical coordinate is 1.
        
        0100: 01 denotes the temperature and 00 denotes the first temperature (usually only one).
        
     c. Example: 010002000300050006000000
        From the beginning, every four digits form a group.
        
        0100: 01 denotes the screen and 00 denotes the first screen (usually only one).
        
        0200: 02 denotes the sending card and 00 denotes the first sending card.
        
        0300: 03 denotes the Ethernet port and 00 denotes the first Ethernet port.
        
        0500: 05 denotes the receiving card and 00 denotes the first receiving card.
        
        0600: 06 denotes the monitor card and 00 denotes the first monitor card.
        
        0000: The first 00 denotes the voltage and the last 00 denotes the first voltage (usually only one) .
        
        
4. Enumeration information table

| Device Type Enumeration Code(decimalism) | Device Type Enumeration Value |
| --- | --- |
| 1(1) | Screen |
| 2(2) | Sending card |
| 3(3) | Ethernet port |
| 5(5) | Receiving card |
| 6(6) | Monitor card |
| C(12) | Smart module |
| D(13) | Horizontal coordinate of the smart module |
| E(14) | Vertical coordinate of the smart module |

| Device Type Enumeration Code(decimalism) | Device Type Enumeration Value |
| --- | --- |
| 0(0) | Voltage |
| 1(1) | Temperature |
| 4(4) | Fan speed |
| 5(5) | Smoke |
| 6(6) | Working status |
| 9(9) | Cabinet door status |
| B(11) | Flat cable status |
| C(12) | Ethernet port redundancy status |
| E(14) | Hardware connection status |


# Topology Information

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/device-status-monitor/receiving-card/topology/{sn}:
    get:
      summary: Topology Information
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for obtaining the topology of the receiving
        cards connected to the current device

        2. This is an advanced interface.

        :::
      tags:
        - VNNOXCare/Device Status Monitoring/Receiving Card
      parameters:
        - name: sn
          in: path
          description: Device SN
          required: true
          schema:
            type: string
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  identifier:
                    type: string
                    description: Device ID
                  redus:
                    type: array
                    items:
                      type: object
                      properties:
                        masterCom:
                          type: string
                          description: Primary Ethernet port
                        reduItems:
                          type: array
                          items:
                            type: object
                            properties:
                              masterPortIndex:
                                type: integer
                                description: Primary Ethernet port index
                              masterSenderIndex:
                                type: integer
                                description: Primary controller index
                              slavePortIndex:
                                type: integer
                                description: Redundant Ethernet port index
                              slaveSenderIndex:
                                type: integer
                                description: Redundant Ethernet port index
                            x-apifox-orders:
                              - masterPortIndex
                              - masterSenderIndex
                              - slavePortIndex
                              - slaveSenderIndex
                        slaveCom:
                          type: string
                          description: Redundant Ethernet port
                      x-apifox-orders:
                        - masterCom
                        - reduItems
                        - slaveCom
                  senders:
                    type: array
                    items:
                      type: object
                      properties:
                        com:
                          type: string
                          description: com port
                        physicalDataKey:
                          type: string
                          description: Enumeration code
                        senderConnectedStatus:
                          type: integer
                          description: Connection status
                        senderFirmwareVersion:
                          type: string
                          description: Hardware version
                        senderIndex:
                          type: integer
                          description: Index
                        senderInputSource:
                          type: string
                          description: Input source
                        senderName:
                          type: string
                          description: Name
                        senderPCBVersion:
                          type: string
                          description: PCB version
                        senderPortsNumber:
                          type: integer
                          description: Number of Ethernet ports
                        senderRate:
                          type: integer
                          description: Frame rate
                        senderTerminalTime:
                          type: string
                          description: Time
                        senderTerminalTimeZone:
                          type: string
                          description: Time zone
                        senderType:
                          type: string
                          description: Device type code
                        senderWorkStatus:
                          type: integer
                          description: Device working status
                      x-apifox-orders:
                        - com
                        - physicalDataKey
                        - senderConnectedStatus
                        - senderFirmwareVersion
                        - senderIndex
                        - senderInputSource
                        - senderName
                        - senderPCBVersion
                        - senderPortsNumber
                        - senderRate
                        - senderTerminalTime
                        - senderTerminalTimeZone
                        - senderType
                        - senderWorkStatus
                    description: Controller information
                  topologys:
                    type: array
                    items:
                      type: object
                      properties:
                        com:
                          type: string
                          description: com port
                        connectIndex:
                          type: integer
                          description: Connection index
                        groupIndex:
                          type: integer
                          description: Group index
                        height:
                          type: integer
                          description: Height
                        physicalDataKey:
                          type: string
                          description: Type enumeration
                        portIndex:
                          type: integer
                          description: Ethernet port index
                        senderIndex:
                          type: integer
                          description: Controller index
                        width:
                          type: integer
                          description: Width
                        x:
                          type: integer
                          description: Horizontal coordinate
                        'y':
                          type: integer
                          description: Vertical coordinate
                      x-apifox-orders:
                        - com
                        - connectIndex
                        - groupIndex
                        - height
                        - physicalDataKey
                        - portIndex
                        - senderIndex
                        - width
                        - x
                        - 'y'
                    description: Topology
                required:
                  - identifier
                  - redus
                  - senders
                  - topologys
                x-apifox-orders:
                  - identifier
                  - redus
                  - senders
                  - topologys
              example:
                identifier: string
                redus:
                  - masterCom: string
                    reduItems:
                      - masterPortIndex: 0
                        masterSenderIndex: 0
                        slavePortIndex: 0
                        slaveSenderIndex: 0
                    slaveCom: string
                senders:
                  - com: string
                    physicalDataKey: string
                    senderConnectedStatus: 0
                    senderFirmwareVersion: string
                    senderIndex: 0
                    senderInputSource: string
                    senderName: string
                    senderPCBVersion: string
                    senderPortsNumber: 0
                    senderRate: 0
                    senderTerminalTime: string
                    senderTerminalTimeZone: string
                    senderType: string
                    senderWorkStatus: 0
                topologys:
                  - com: string
                    connectIndex: 0
                    groupIndex: 0
                    height: 0
                    physicalDataKey: string
                    portIndex: 0
                    senderIndex: 0
                    width: 0
                    x: 0
                    'y': 0
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: VNNOXCare/Device Status Monitoring/Receiving Card
      x-apifox-status: developing
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-198265532-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```

# Basic Information

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/device-status-monitor/receiving-card/basics/{sn}:
    get:
      summary: Basic Information
      deprecated: false
      description: >-

        :::tip

        1. This interface can be used for obtaining the basic information of the
        receiving cards loaded by the current device.

        2. Advanced interface.

        :::
      tags:
        - VNNOXCare/Device Status Monitoring/Receiving Card
      parameters:
        - name: sn
          in: path
          description: Device SN
          required: true
          schema:
            type: string
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  items:
                    type: array
                    items:
                      type: object
                      properties:
                        fpgaVersion:
                          type: string
                          description: FPGA version
                        mcuVersion:
                          type: string
                          description: MCU version
                        modelType:
                          type: string
                          description: Device model
                        position:
                          type: string
                          description: Location
                        sn:
                          type: string
                          description: Device SN
                      required:
                        - fpgaVersion
                        - mcuVersion
                        - modelType
                        - position
                        - sn
                      x-apifox-orders:
                        - fpgaVersion
                        - mcuVersion
                        - modelType
                        - position
                        - sn
                    description: Receiving card information list
                required:
                  - items
                x-apifox-orders:
                  - items
              example:
                items:
                  - fpgaVersion: FPGA：4.6.4.119
                    mcuVersion: MCU：4.6.4.119
                    modelType: A8s
                    position: '020003000500'
                    sn: 2YHA12706N1A10049941
                  - fpgaVersion: FPGA：4.6.4.119
                    mcuVersion: MCU：4.6.4.119
                    modelType: A8s
                    position: '020003000501'
                    sn: 2YHA12706N1A10049941
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: VNNOXCare/Device Status Monitoring/Receiving Card
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-181820138-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Monitoring Information

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/device-status-monitor/receiving-card/monitor/{sn}:
    get:
      summary: Monitoring Information
      deprecated: false
      description: >-

        :::tip

        1. This interface can be used for obtaining the monitoring information
        of the receiving cards loaded by the current device.

        2. Advanced interface.

        :::
      tags:
        - VNNOXCare/Device Status Monitoring/Receiving Card
      parameters:
        - name: sn
          in: path
          description: Device SN
          required: true
          schema:
            type: string
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  items:
                    type: array
                    items:
                      type: object
                      properties:
                        deviceWorkState:
                          type: boolean
                          description: 'Status: true: normal, false: abnormal'
                        position:
                          type: string
                          description: Location
                        sn:
                          type: string
                          description: Device SN
                        temperature:
                          type: string
                          description: temperature
                        voltage:
                          type: string
                          description: voltage
                      required:
                        - deviceWorkState
                        - position
                        - sn
                        - temperature
                        - voltage
                      x-apifox-orders:
                        - deviceWorkState
                        - position
                        - sn
                        - temperature
                        - voltage
                required:
                  - items
                x-apifox-orders:
                  - items
              example:
                items:
                  - status: true
                    position: '020003000500'
                    sn: 2YHA12706N1A10049941
                    temperature: '37.0'
                    voltage: '4.9'
                  - status: false
                    position: '020003000501'
                    sn: 2YHA12706N1A10049941
                    temperature: '37.0'
                    voltage: '4.9'
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: VNNOXCare/Device Status Monitoring/Receiving Card
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-181820139-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Alarm Information

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/device-status-monitor/receiving-card/alarm/{sn}:
    get:
      summary: Alarm Information
      deprecated: false
      description: >-

        :::tip

        1. This interface can be used for obtaining the alarm information of the
        receiving cards loaded by the current device.

        2. Advanced interface.

        :::
      tags:
        - VNNOXCare/Device Status Monitoring/Receiving Card
      parameters:
        - name: sn
          in: path
          description: ''
          required: true
          schema:
            type: string
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  items:
                    type: array
                    items:
                      type: object
                      properties:
                        alarmFrom:
                          type: string
                          description: Source of alarm hardware device
                        alarmType:
                          type: string
                          description: Alarm Type
                        alarmValue:
                          type: integer
                          description: Alarm Value
                        createAt:
                          type: integer
                          description: Creation Time
                        level:
                          type: integer
                          description: 'Alarm level: 3 - Risk; 4 - Fault'
                        position:
                          type: string
                          description: Location
                        sn:
                          type: string
                          description: Device SN
                        alarmTime:
                          type: string
                          description: >-
                            The time zone of the alarm time is consistent with
                            the time zone of the alarm rule.
                      required:
                        - alarmFrom
                        - alarmTime
                        - alarmType
                        - alarmValue
                        - createAt
                        - level
                        - position
                        - sn
                      x-apifox-orders:
                        - alarmFrom
                        - alarmTime
                        - alarmType
                        - alarmValue
                        - createAt
                        - level
                        - position
                        - sn
                required:
                  - items
                x-apifox-orders:
                  - items
              example:
                items:
                  - alarmFrom: '5'
                    alarmStart: '2024-05-07 20:55:24'
                    alarmType: '0'
                    alarmValue: 0
                    createAt: 1715086524688
                    level: 3
                    position: 010002000300054F0000
                    sn: 2YHA12705N3A10048835
                  - alarmFrom: '5'
                    alarmStart: '2024-05-07 20:55:24'
                    alarmType: '0'
                    alarmValue: 0
                    createAt: 1715086524688
                    level: 3
                    position: 010002000300054F0000
                    sn: 2YHA12705N3A10048835
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: VNNOXCare/Device Status Monitoring/Receiving Card
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-181820140-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Screen list

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/device-status-monitor/screen/list:
    get:
      summary: Screen list
      deprecated: false
      description: "\n:::tip\n1. This\_interface\_can\_be\_used\_for\_obtaining\_the\_screen\_list.\n2. Advanced interface.\n:::"
      tags:
        - VNNOXCare/Device Status Monitoring/Screen
      parameters:
        - name: pageNumber
          in: query
          description: Page number
          required: true
          example: 0
          schema:
            type: integer
            minimum: 0
        - name: pageSize
          in: query
          description: Number of records per page
          required: true
          example: 1000
          schema:
            type: integer
            minimum: 1
            maximum: 1000
        - name: status
          in: query
          description: 'Screen status 1: Normal, 2: Offline, 3: Risky, 4: Faulty'
          required: false
          example: 1
          schema:
            type: integer
        - name: name
          in: query
          description: Screen name
          required: false
          schema:
            type: string
        - name: address
          in: query
          description: Screen location
          required: false
          schema:
            type: string
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  total:
                    type: integer
                    description: Total number of records
                  items:
                    type: array
                    items:
                      type: object
                      properties:
                        sid:
                          type: integer
                          description: Screen ID
                        name:
                          type: string
                          description: Screen name
                        mac:
                          type: string
                          description: Screen MAC address
                        sn:
                          type: string
                          description: Unique identifier of the screen
                        address:
                          type: string
                          description: Screen location
                        longitude:
                          type: number
                          description: Screen longitude
                        latitude:
                          type: number
                          description: Screen latitude
                        status:
                          type: integer
                          description: Screen status
                        camera:
                          type: integer
                          description: 'Does the screen have a camera? 0: No, 1: Yes.'
                        brightness:
                          type: integer
                          description: Screen brightness
                        envBrightness:
                          type: integer
                          description: >-
                            Ambient brightness -1 means the brightness is not
                            obtained.
                      x-apifox-orders:
                        - sid
                        - name
                        - mac
                        - sn
                        - address
                        - longitude
                        - latitude
                        - status
                        - camera
                        - brightness
                        - envBrightness
                      required:
                        - sid
                        - name
                        - mac
                        - sn
                        - address
                        - longitude
                        - latitude
                        - status
                        - camera
                        - brightness
                        - envBrightness
                required:
                  - total
                  - items
                x-apifox-orders:
                  - total
                  - items
              example:
                total: 100
                items:
                  - sid: 1
                    name: TU20
                    mac: 00FF5C24E412
                    sn: 12C34A567890388
                    address: New York
                    longitude: -74.00109387752458
                    latitude: 40.73674273658566
                    status: 1
                    camera: 1
                    brightness: 84
                    envBrightness: 10000
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: VNNOXCare/Device Status Monitoring/Screen
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-262298538-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Screen detail

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/device-status-monitor/all:
    post:
      summary: Screen detail
      deprecated: false
      description: >-

        :::tip

        1. The display details can be obtained through this interface, and the
        details of up to 10 displays can be obtained at a time.

        2. Advanced interface.

        :::
      tags:
        - VNNOXCare/Device Status Monitoring/Screen
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                snList:
                  type: array
                  items:
                    type: string
                  description: SN list, a maximum of 10 data at a time
              required:
                - snList
              x-apifox-orders:
                - snList
            example:
              snList:
                - 2YHA23C12A3A10080149
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  value:
                    type: array
                    items:
                      type: object
                      properties:
                        identifier:
                          type: string
                          description: Screen identifier
                        inputSource:
                          type: object
                          properties:
                            monitor:
                              type: object
                              properties:
                                computer:
                                  type: array
                                  items:
                                    type: object
                                    properties:
                                      name:
                                        type: string
                                        description: Drive letter
                                      surplus:
                                        type: string
                                        description: Free space (GB)
                                      total:
                                        type: string
                                        description: Total space (GB)
                                      type:
                                        type: string
                                        description: Drive type
                                      used:
                                        type: string
                                        description: Used space (GB)
                                      volumeName:
                                        type: string
                                        description: Volume label
                                    x-apifox-orders:
                                      - name
                                      - surplus
                                      - total
                                      - type
                                      - used
                                      - volumeName
                                    description: PC drive information collection
                                  description: PC drive information
                                cpu:
                                  type: integer
                                  description: CPU usage
                                memSurplus:
                                  type: string
                                  description: Free RAM
                                memTotal:
                                  type: string
                                  description: Total RAM
                                memUsed:
                                  type: string
                                  description: Used RAM
                                sid:
                                  type: integer
                                  description: Device unique identification
                                system:
                                  type: object
                                  properties:
                                    bit64:
                                      type: boolean
                                      description: 64 bit
                                    version:
                                      type: string
                                      description: System version
                                  required:
                                    - bit64
                                    - version
                                  x-apifox-orders:
                                    - bit64
                                    - version
                                  description: System information
                              required:
                                - computer
                                - cpu
                                - memSurplus
                                - memTotal
                                - memUsed
                                - sid
                                - system
                              x-apifox-orders:
                                - computer
                                - cpu
                                - memSurplus
                                - memTotal
                                - memUsed
                                - sid
                                - system
                              description: Monitor data
                          required:
                            - monitor
                          x-apifox-orders:
                            - monitor
                          description: Input source
                        mac:
                          type: string
                          description: MAC
                        masterControl:
                          type: object
                          properties:
                            alarm:
                              type: array
                              items:
                                type: object
                                properties:
                                  alarmFrom:
                                    type: string
                                    description: Source of alarm hardware device
                                  alarmStart:
                                    type: string
                                    description: >-
                                      The time zone of the alarm time is
                                      consistent with the time zone of the alarm
                                      rule.
                                  alarmType:
                                    type: string
                                    description: Alarm Type
                                  alarmValue:
                                    type: string
                                    description: Alarm Value
                                  createAt:
                                    type: integer
                                    description: Creation Time
                                  level:
                                    type: integer
                                    description: 'Alarm level: 3 - Risk; 4 - Fault'
                                  position:
                                    type: string
                                    description: Location
                                x-apifox-orders:
                                  - alarmFrom
                                  - alarmStart
                                  - alarmType
                                  - alarmValue
                                  - createAt
                                  - level
                                  - position
                                description: Alarm information collection
                                required:
                                  - alarmValue
                              description: Alarm information
                            basics:
                              type: object
                              properties:
                                networkPortsNum:
                                  type: integer
                                  description: Number of Ethernet ports
                                reportTime:
                                  type: string
                                  description: Reporting time of monitoring data
                                signalSource:
                                  type: string
                                  description: Signal source
                                status:
                                  type: boolean
                                  description: >-
                                    Controller status: true-normal,
                                    false-abnormal
                                timeZone:
                                  type: string
                                  description: Terminal Time Zone
                                version:
                                  type: string
                                  description: Software version
                              required:
                                - networkPortsNum
                                - reportTime
                                - signalSource
                                - status
                                - timeZone
                                - version
                              x-apifox-orders:
                                - networkPortsNum
                                - reportTime
                                - signalSource
                                - status
                                - timeZone
                                - version
                              description: Basic information
                            running:
                              type: object
                              properties:
                                basic:
                                  type: object
                                  properties:
                                    availableMemory:
                                      type: string
                                      description: Free Memory
                                    availableStorageSpace:
                                      type: string
                                      description: Free storage space
                                    cpuUsageRate:
                                      type: string
                                      description: CPU usage
                                    playingStatus:
                                      type: string
                                      description: Playback status
                                    resolutionRatio:
                                      type: string
                                      description: Resolution
                                    screenStatus:
                                      type: string
                                      description: Screen status
                                    sync:
                                      type: boolean
                                      description: Synchronous playback
                                    volume:
                                      type: string
                                      description: Volume
                                  required:
                                    - availableMemory
                                    - availableStorageSpace
                                    - cpuUsageRate
                                    - playingStatus
                                    - resolutionRatio
                                    - screenStatus
                                    - sync
                                    - volume
                                  x-apifox-orders:
                                    - availableMemory
                                    - availableStorageSpace
                                    - cpuUsageRate
                                    - playingStatus
                                    - resolutionRatio
                                    - screenStatus
                                    - sync
                                    - volume
                                  description: Basic Information
                                lora:
                                  type: object
                                  properties:
                                    enableLora:
                                      type: boolean
                                      description: On/Off status
                                    existLora:
                                      type: boolean
                                      description: A 4G module is detected
                                    funLora:
                                      type: object
                                      properties:
                                        volumeEnable:
                                          type: string
                                        brightnessEnable:
                                          type: string
                                        environmentalMonitoring:
                                          type: string
                                        timeEnable:
                                          type: string
                                      x-apifox-orders:
                                        - volumeEnable
                                        - brightnessEnable
                                        - environmentalMonitoring
                                        - timeEnable
                                      description: Functions using RF
                                      required:
                                        - volumeEnable
                                        - timeEnable
                                        - brightnessEnable
                                        - environmentalMonitoring
                                    groupId:
                                      type: string
                                      description: Group ID
                                    mode:
                                      type: string
                                      description: Master/Slave device
                                  required:
                                    - enableLora
                                    - existLora
                                    - funLora
                                    - groupId
                                    - mode
                                  x-apifox-orders:
                                    - enableLora
                                    - existLora
                                    - funLora
                                    - groupId
                                    - mode
                                  description: RF management
                                netWork:
                                  type: object
                                  properties:
                                    dhcp:
                                      type: string
                                      description: DHCP
                                    dns:
                                      type: string
                                      description: DNS
                                    gateWay:
                                      type: string
                                      description: GateWay
                                    ip:
                                      type: string
                                      description: IP
                                    mask:
                                      type: string
                                      description: Mask
                                    networkType:
                                      type: string
                                      description: >-
                                        Network mode, network type (0: no
                                        network, 1: wired network, 2: Wi-Fi, 3:
                                        2G, 4: 3G, 5: 4G)
                                  required:
                                    - dhcp
                                    - dns
                                    - gateWay
                                    - ip
                                    - mask
                                    - networkType
                                  x-apifox-orders:
                                    - dhcp
                                    - dns
                                    - gateWay
                                    - ip
                                    - mask
                                    - networkType
                                  description: Network parameters
                                rebotConfig:
                                  type: object
                                  properties:
                                    enable:
                                      type: array
                                      items:
                                        type: string
                                      description: Enable
                                    repetitionMethod:
                                      type: array
                                      items:
                                        type: string
                                      description: Repetition method
                                    time:
                                      type: array
                                      items:
                                        type: string
                                      description: Time
                                  required:
                                    - enable
                                    - repetitionMethod
                                    - time
                                  x-apifox-orders:
                                    - enable
                                    - repetitionMethod
                                    - time
                                  description: Restart configuration schedule
                                screen:
                                  type: object
                                  properties:
                                    mode:
                                      type: string
                                      description: >-
                                        Screen control mode (MANUALLY means to
                                        control the screen manually. AUTO means
                                        to control the screen automatically.)
                                    state:
                                      type: string
                                      description: >-
                                        Restart adjustment mode ("OPEN": on,
                                        "CLOSE": off)
                                  required:
                                    - mode
                                    - state
                                  x-apifox-orders:
                                    - mode
                                    - state
                                  description: Screen
                                screenConfig:
                                  type: object
                                  properties:
                                    action:
                                      type: string
                                      description: 'Screen control (OPEN: on, CLOSE: off)'
                                    enable:
                                      type: boolean
                                      description: Enable
                                    repetitionMethod:
                                      type: string
                                      description: Repetition method
                                    time:
                                      type: string
                                      description: Time
                                  required:
                                    - action
                                    - enable
                                    - repetitionMethod
                                    - time
                                  x-apifox-orders:
                                    - action
                                    - enable
                                    - repetitionMethod
                                    - time
                                  description: Screen configuration schedule
                                sensor:
                                  type: object
                                  properties:
                                    sensorId:
                                      type: array
                                      items:
                                        type: string
                                      description: Sensor
                                    vendorAliasName:
                                      type: array
                                      items:
                                        type: string
                                      description: Manufacturer
                                  required:
                                    - sensorId
                                    - vendorAliasName
                                  x-apifox-orders:
                                    - sensorId
                                    - vendorAliasName
                                  description: Sensor parameters
                                timeInfo:
                                  type: object
                                  properties:
                                    time:
                                      type: string
                                      description: Time
                                    timeSyncMode:
                                      type: boolean
                                      description: Time synchronization method
                                    timeZone:
                                      type: string
                                      description: |
                                        TimeZone
                                  required:
                                    - time
                                    - timeSyncMode
                                    - timeZone
                                  x-apifox-orders:
                                    - time
                                    - timeSyncMode
                                    - timeZone
                                  description: TimeConfig
                                videoConfig:
                                  type: object
                                  properties:
                                    enable:
                                      type: boolean
                                      description: Enable
                                    repetitionMethod:
                                      type: string
                                      description: Repetition method
                                    sourceTpye:
                                      type: string
                                      description: >-
                                        Adjustment type (Internal: 0, HDMI: 1)
                                        (This field is only available in manual
                                        mode.)
                                    time:
                                      type: string
                                      description: Time
                                  required:
                                    - enable
                                    - repetitionMethod
                                    - sourceTpye
                                    - time
                                  x-apifox-orders:
                                    - enable
                                    - repetitionMethod
                                    - sourceTpye
                                    - time
                                  description: Video source configuration schedule
                                videoSource:
                                  type: object
                                  properties:
                                    hdmiSource:
                                      type: string
                                      description: HDMI source
                                    internalSource:
                                      type: string
                                      description: Internal source
                                    offSet:
                                      type: string
                                      description: Output coordinates
                                    videoMode:
                                      type: integer
                                      description: >-
                                        Video source mode (HDMI preferred 0,
                                        manual 1, scheduled 2)
                                    videoSource:
                                      type: integer
                                      description: >-
                                        Video source type (0-internal source,
                                        1-external source)
                                  required:
                                    - internalSource
                                    - offSet
                                    - videoMode
                                    - videoSource
                                    - hdmiSource
                                  x-apifox-orders:
                                    - hdmiSource
                                    - internalSource
                                    - offSet
                                    - videoMode
                                    - videoSource
                                  description: Video source
                              required:
                                - basic
                                - lora
                                - netWork
                                - rebotConfig
                                - screen
                                - screenConfig
                                - sensor
                                - timeInfo
                                - videoConfig
                                - videoSource
                              x-apifox-orders:
                                - basic
                                - lora
                                - netWork
                                - rebotConfig
                                - screen
                                - screenConfig
                                - sensor
                                - timeInfo
                                - videoConfig
                                - videoSource
                              description: Operating Parameters Information
                          required:
                            - alarm
                            - basics
                            - running
                          x-apifox-orders:
                            - alarm
                            - basics
                            - running
                          description: Master control information
                        module:
                          type: object
                          properties:
                            monitor:
                              type: array
                              items:
                                type: object
                                properties:
                                  height:
                                    type: integer
                                    description: Module height
                                  icType:
                                    type: string
                                    description: Driver IC type
                                  lineDecodingIc:
                                    type: string
                                    description: Decoder IC type
                                  scanNum:
                                    type: string
                                    description: Number of scans
                                  width:
                                    type: integer
                                    description: Module width
                                x-apifox-orders:
                                  - height
                                  - width
                                  - icType
                                  - lineDecodingIc
                                  - scanNum
                                description: Module/Cabinet information list
                              description: Monitor information
                          required:
                            - monitor
                          x-apifox-orders:
                            - monitor
                          description: Module/Cabinet information
                        monitorCard:
                          type: object
                          properties:
                            monitor:
                              type: array
                              items:
                                type: object
                                properties:
                                  cabinDoorStatus:
                                    type: boolean
                                    description: Cabinet door status
                                  fansSpeed:
                                    type: object
                                    properties:
                                      additionalProp1:
                                        type: integer
                                      additionalProp2:
                                        type: integer
                                      additionalProp3:
                                        type: integer
                                    required:
                                      - additionalProp1
                                      - additionalProp2
                                      - additionalProp3
                                    x-apifox-orders:
                                      - additionalProp1
                                      - additionalProp2
                                      - additionalProp3
                                    description: Fan speed
                                  position:
                                    type: string
                                    description: Location
                                  smokeStatus:
                                    type: boolean
                                    description: Smoke
                                  sn:
                                    type: string
                                    description: Device SN
                                  socketCableStatus:
                                    type: array
                                    items:
                                      type: boolean
                                    description: Flat cable status
                                  status:
                                    type: boolean
                                    description: Working status
                                  voltage:
                                    type: array
                                    items:
                                      type: string
                                    description: Voltage
                                x-apifox-orders:
                                  - cabinDoorStatus
                                  - fansSpeed
                                  - position
                                  - smokeStatus
                                  - sn
                                  - socketCableStatus
                                  - status
                                  - voltage
                                description: Monitor information collection
                              description: Monitor information
                          required:
                            - monitor
                          x-apifox-orders:
                            - monitor
                          description: Monitoring card information
                        receivingCard:
                          type: object
                          properties:
                            alarm:
                              type: array
                              items:
                                type: object
                                properties:
                                  alarmFrom:
                                    type: string
                                  alarmStart:
                                    type: string
                                  alarmType:
                                    type: string
                                  alarmValue:
                                    type: string
                                    description: Alarm value
                                  createAt:
                                    type: integer
                                  level:
                                    type: integer
                                  position:
                                    type: string
                                x-apifox-orders:
                                  - alarmFrom
                                  - alarmStart
                                  - alarmType
                                  - alarmValue
                                  - createAt
                                  - level
                                  - position
                                required:
                                  - alarmValue
                                description: Alarm information collection
                              description: Alarm information
                            basics:
                              type: array
                              items:
                                type: object
                                properties:
                                  fpgaVersion:
                                    type: string
                                    description: FPGA version
                                  mcuVersion:
                                    type: string
                                    description: MCU version
                                  modelType:
                                    type: string
                                    description: Device model
                                  position:
                                    type: string
                                    description: |
                                      Location
                                x-apifox-orders:
                                  - fpgaVersion
                                  - mcuVersion
                                  - modelType
                                  - position
                                description: Basic information collection
                              description: Basic information
                            monitor:
                              type: array
                              items:
                                type: object
                                properties:
                                  position:
                                    type: string
                                    description: Location
                                  status:
                                    type: boolean
                                    description: 'Status: true: normal, false: abnormal'
                                  temperature:
                                    type: string
                                    description: temperature
                                  voltage:
                                    type: string
                                    description: voltage
                                x-apifox-orders:
                                  - status
                                  - position
                                  - temperature
                                  - voltage
                                description: Monitor information collection
                              description: Monitor information
                            topology:
                              type: object
                              properties:
                                redus:
                                  type: array
                                  items:
                                    type: object
                                    properties:
                                      masterCom:
                                        type: string
                                        description: Primary Ethernet port
                                      reduItems:
                                        type: array
                                        items:
                                          type: object
                                          properties:
                                            masterPortIndex:
                                              type: integer
                                              description: Primary Ethernet port index
                                            masterSenderIndex:
                                              type: integer
                                              description: Primary controller index
                                            slavePortIndex:
                                              type: integer
                                              description: Redundant Ethernet port index
                                            slaveSenderIndex:
                                              type: integer
                                              description: Redundant Ethernet port index
                                          x-apifox-orders:
                                            - masterPortIndex
                                            - masterSenderIndex
                                            - slavePortIndex
                                            - slaveSenderIndex
                                      slaveCom:
                                        type: string
                                        description: Redundant Ethernet port
                                    x-apifox-orders:
                                      - masterCom
                                      - reduItems
                                      - slaveCom
                                    description: Redus Information collection
                                  description: Redus Information
                                senders:
                                  type: array
                                  items:
                                    type: object
                                    properties:
                                      com:
                                        type: string
                                        description: com port
                                      physicalDataKey:
                                        type: string
                                        description: Enumeration code
                                      senderConnectedStatus:
                                        type: integer
                                        description: Connection status
                                      senderFirmwareVersion:
                                        type: string
                                        description: Hardware version
                                      senderIndex:
                                        type: integer
                                        description: Index
                                      senderInputSource:
                                        type: string
                                        description: Input source
                                      senderName:
                                        type: string
                                        description: Name
                                      senderPCBVersion:
                                        type: string
                                        description: PCB version
                                      senderPortsNumber:
                                        type: integer
                                        description: Number of Ethernet ports
                                      senderRate:
                                        type: integer
                                        description: Frame rate
                                      senderTerminalTime:
                                        type: string
                                        description: Time
                                      senderTerminalTimeZone:
                                        type: string
                                        description: TimeZone
                                      senderType:
                                        type: string
                                        description: Device type code
                                      senderWorkStatus:
                                        type: integer
                                        description: Device working status
                                    x-apifox-orders:
                                      - com
                                      - physicalDataKey
                                      - senderConnectedStatus
                                      - senderFirmwareVersion
                                      - senderIndex
                                      - senderInputSource
                                      - senderName
                                      - senderPCBVersion
                                      - senderPortsNumber
                                      - senderRate
                                      - senderTerminalTime
                                      - senderTerminalTimeZone
                                      - senderType
                                      - senderWorkStatus
                                    description: Controller information collection
                                  description: Controller information
                                topologys:
                                  type: array
                                  items:
                                    type: object
                                    properties:
                                      com:
                                        type: string
                                        description: com port
                                      connectIndex:
                                        type: integer
                                        description: Connection index
                                      groupIndex:
                                        type: integer
                                        description: Group index
                                      height:
                                        type: integer
                                        description: Height
                                      physicalDataKey:
                                        type: string
                                        description: Type enumeration
                                      portIndex:
                                        type: integer
                                        description: Ethernet port index
                                      senderIndex:
                                        type: integer
                                        description: Controller index
                                      width:
                                        type: integer
                                        description: Width
                                      x:
                                        type: integer
                                        description: Horizontal coordinate
                                      'y':
                                        type: integer
                                        description: Vertical coordinate
                                    x-apifox-orders:
                                      - com
                                      - connectIndex
                                      - groupIndex
                                      - height
                                      - physicalDataKey
                                      - portIndex
                                      - senderIndex
                                      - width
                                      - x
                                      - 'y'
                                    description: Topology information collection
                                  description: Topology information
                              required:
                                - redus
                                - senders
                                - topologys
                              x-apifox-orders:
                                - redus
                                - senders
                                - topologys
                              description: Topology information
                          required:
                            - alarm
                            - basics
                            - monitor
                            - topology
                          x-apifox-orders:
                            - alarm
                            - basics
                            - monitor
                            - topology
                          description: Receiving card information
                        screen:
                          type: object
                          properties:
                            monitor:
                              type: object
                              properties:
                                brightness:
                                  type: integer
                                  description: Screen brightness
                                displayDevice:
                                  type: string
                                  description: 'Screen type: LED or LCD'
                                envBrightness:
                                  type: integer
                                  description: >-
                                    Ambient brightness -1 means the brightness
                                    is not obtained.
                                height:
                                  type: integer
                                  description: Screen height
                                width:
                                  type: integer
                                  description: Screen width
                              required:
                                - brightness
                                - displayDevice
                                - envBrightness
                                - height
                                - width
                              x-apifox-orders:
                                - brightness
                                - displayDevice
                                - envBrightness
                                - height
                                - width
                              description: Monitor inforamation
                          required:
                            - monitor
                          x-apifox-orders:
                            - monitor
                          description: Screen information
                        sid:
                          type: integer
                          description: Device unique identification
                        smartModule:
                          type: object
                          properties:
                            alarm:
                              type: array
                              items:
                                type: object
                                properties:
                                  alarmFrom:
                                    type: string
                                  alarmStart:
                                    type: string
                                  alarmType:
                                    type: string
                                  alarmValue:
                                    type: string
                                    description: Alarm value
                                  createAt:
                                    type: string
                                  level:
                                    type: string
                                  position:
                                    type: string
                                  sn:
                                    type: string
                                x-apifox-orders:
                                  - alarmFrom
                                  - alarmStart
                                  - alarmType
                                  - alarmValue
                                  - createAt
                                  - level
                                  - position
                                  - sn
                                required:
                                  - alarmValue
                            monitor:
                              type: array
                              items:
                                type: object
                                properties:
                                  flatCableStatus:
                                    type: boolean
                                    description: >-
                                      Flat cable connection status: true -
                                      normal; false - abnormal.
                                  position:
                                    type: string
                                    description: Position
                                  runtime:
                                    type: string
                                    description: Running time in min
                                  sn:
                                    type: string
                                    description: Device serial number
                                  status:
                                    type: boolean
                                    description: >-
                                      Device status: true - normal; false -
                                      abnormal
                                  temperature:
                                    type: string
                                    description: Temperature
                                  voltage:
                                    type: string
                                    description: Voltage
                                x-apifox-orders:
                                  - flatCableStatus
                                  - position
                                  - runtime
                                  - sn
                                  - status
                                  - temperature
                                  - voltage
                                description: Monitor information collection
                              description: Monitor information
                          required:
                            - alarm
                            - monitor
                          x-apifox-orders:
                            - alarm
                            - monitor
                          description: Smart module information
                        sn:
                          type: string
                          description: Device serial number
                      x-apifox-orders:
                        - identifier
                        - inputSource
                        - mac
                        - masterControl
                        - module
                        - monitorCard
                        - receivingCard
                        - screen
                        - sid
                        - smartModule
                        - sn
                      description: Screen detail information collection
                    description: Screen detail information
                required:
                  - value
                x-apifox-orders:
                  - value
              example:
                value:
                  - identifier: string
                    inputSource:
                      monitor:
                        computer:
                          - name: string
                            surplus: string
                            total: string
                            type: string
                            used: string
                            volumeName: string
                        cpu: 0
                        memSurplus: string
                        memTotal: string
                        memUsed: string
                        sid: 0
                        system:
                          bit64: true
                          version: string
                    mac: string
                    masterControl:
                      alarm:
                        - alarmFrom: string
                          alarmStart: string
                          alarmType: string
                          alarmValue: {}
                          createAt: 0
                          level: 0
                          position: string
                      basics:
                        networkPortsNum: 0
                        reportTime: string
                        signalSource: string
                        status: false
                        timeZone: string
                        version: string
                      running:
                        basic:
                          availableMemory: {}
                          availableStorageSpace: {}
                          cpuUsageRate: {}
                          playingStatus: {}
                          resolutionRatio: string
                          screenStatus: {}
                          sync: false
                          volume: {}
                        lora:
                          enableLora: {}
                          existLora: {}
                          funLora: {}
                          groupId: {}
                          mode: {}
                        netWork:
                          dhcp: {}
                          dns: {}
                          gateWay: {}
                          ip: {}
                          mask: {}
                          networkType: {}
                        rebotConfig:
                          enable: {}
                          repetitionMethod: {}
                          time: {}
                        screen:
                          mode: {}
                          state: {}
                        screenConfig:
                          action: {}
                          enable: {}
                          repetitionMethod: {}
                          time: {}
                        sensor:
                          sensorId: {}
                          vendorAliasName: {}
                        timeInfo:
                          time: {}
                          timeSyncMode: {}
                          timeZone: {}
                        videoConfig:
                          enable: {}
                          repetitionMethod: {}
                          sourceTpye: {}
                          time: {}
                        videoSource:
                          hdmiSource: {}
                          internalSource: {}
                          offSet: {}
                          videoMode: {}
                          videoSource: {}
                    module:
                      monitor:
                        - height: 0
                          icType: string
                          lineDecodingIc: string
                          scanNum: string
                          width: 0
                    monitorCard:
                      monitor:
                        - cabinDoorStatus: true
                          fansSpeed:
                            additionalProp1: 0
                            additionalProp2: 0
                            additionalProp3: 0
                          position: string
                          smokeStatus: true
                          sn: string
                          socketCableStatus:
                            - true
                          status: true
                          voltage:
                            additionalProp1: 0
                            additionalProp2: 0
                            additionalProp3: 0
                    receivingCard:
                      alarm:
                        - alarmFrom: string
                          alarmStart: string
                          alarmType: string
                          alarmValue: {}
                          createAt: 0
                          level: 0
                          position: string
                      basics:
                        - fpgaVersion: string
                          mcuVersion: string
                          modelType: string
                          position: string
                      monitor:
                        - position: string
                          status: false
                          temperature: string
                          voltage: string
                      topology:
                        redus:
                          - masterCom: string
                            reduItems:
                              - masterPortIndex: 0
                                masterSenderIndex: 0
                                slavePortIndex: 0
                                slaveSenderIndex: 0
                            slaveCom: string
                        senders:
                          - com: string
                            physicalDataKey: string
                            senderConnectedStatus: 0
                            senderFirmwareVersion: string
                            senderIndex: 0
                            senderInputSource: string
                            senderName: string
                            senderPCBVersion: string
                            senderPortsNumber: 0
                            senderRate: 0
                            senderTerminalTime: string
                            senderTerminalTimeZone: string
                            senderType: string
                            senderWorkStatus: 0
                        topologys:
                          - com: string
                            connectIndex: 0
                            groupIndex: 0
                            height: 0
                            physicalDataKey: string
                            portIndex: 0
                            senderIndex: 0
                            width: 0
                            x: 0
                            'y': 0
                    screen:
                      monitor:
                        brightness: 0
                        displayDevice: string
                        envBrightness: 0
                        height: 0
                        width: 0
                    sid: 0
                    smartModule:
                      alarm:
                        - alarmFrom: {}
                          alarmStart: {}
                          alarmType: {}
                          alarmValue: {}
                          createAt: {}
                          level: {}
                          position: {}
                          sn: {}
                      monitor:
                        - flatCableStatus: false
                          position: {}
                          runtime: {}
                          sn: {}
                          status: false
                          temperature: {}
                          voltage: {}
                    sn: string
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: VNNOXCare/Device Status Monitoring/Screen
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-289009793-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```



# Monitoring Information

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/device-status-monitor/screen/monitor/{sn}:
    get:
      summary: Monitoring Information
      deprecated: false
      description: >-

        :::tip

        1. This interface can be used for obtaining the basic monitoring
        information of the current device.

        2. Advanced interface.

        :::
      tags:
        - VNNOXCare/Device Status Monitoring/Screen
      parameters:
        - name: sn
          in: path
          description: Device SN
          required: true
          schema:
            type: string
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  displayDevice:
                    type: string
                    description: 'Screen type: LED or LCD'
                  brightness:
                    type: integer
                    description: Screen brightness
                  envBrightness:
                    type: integer
                    description: >-
                      Ambient brightness -1 means the brightness is not
                      obtained.
                  height:
                    type: integer
                    description: Screen height
                  width:
                    type: integer
                    description: Screen width
                  sn:
                    type: string
                    description: Device SN
                required:
                  - displayDevice
                  - brightness
                  - envBrightness
                  - height
                  - width
                  - sn
                x-apifox-orders:
                  - displayDevice
                  - brightness
                  - envBrightness
                  - height
                  - width
                  - sn
              example:
                displayDevice: LED
                brightness: 50
                envBrightness: -1
                height: 50
                width: 100
                sn: 2YHA12706N1A10049941
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: VNNOXCare/Device Status Monitoring/Screen
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-181820141-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Basic Information

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/device-status-monitor/master-control/basics/{sn}:
    get:
      summary: Basic Information
      deprecated: false
      description: >-

        :::tip

        1. This interface can be used for obtaining the basic monitoring
        information of the current controller.

        2. Advanced interface.

        :::
      tags:
        - VNNOXCare/Device Status Monitoring/Master Controller
      parameters:
        - name: sn
          in: path
          description: Device SN
          required: true
          schema:
            type: string
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  status:
                    type: integer
                    description: 'Controller status: true-normal, false-abnormal'
                  signalSource:
                    type: string
                    description: Signal source
                  networkPortsNum:
                    type: integer
                    description: Number of Ethernet ports
                  version:
                    type: string
                    description: Software version
                  sn:
                    type: string
                    description: Device SN
                  mac:
                    type: string
                    description: MAC address
                  timeZone:
                    type: string
                    description: Terminal Time Zone
                  reportTime:
                    type: string
                    description: Reporting time of monitoring data
                required:
                  - status
                  - signalSource
                  - networkPortsNum
                  - version
                  - sn
                  - mac
                  - timeZone
                  - reportTime
                x-apifox-orders:
                  - status
                  - signalSource
                  - networkPortsNum
                  - version
                  - sn
                  - mac
                  - timeZone
                  - reportTime
              example:
                status: true
                signalSource: Internal source
                networkPortsNum: 6
                version: 4.3.0.1001
                sn: 2YHA23504W4A10034783-00
                mac: 54B56C0F055E
                timeZone: UTC+08:00
                reportTime: '2024-05-20 23:05:07'
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: VNNOXCare/Device Status Monitoring/Master Controller
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-181820142-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```

# Alarm Information

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/device-status-monitor/master-control/alarm/{sn}:
    get:
      summary: Alarm Information
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for obtaining the current alarm information of
        the controller.

        2. Advanced interface.

        :::
      tags:
        - VNNOXCare/Device Status Monitoring/Master Controller
      parameters:
        - name: sn
          in: path
          description: Device SN
          required: true
          schema:
            type: string
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  items:
                    type: array
                    items:
                      type: object
                      properties:
                        alarmFrom:
                          type: string
                          description: Source of alarm hardware device
                        alarmStart:
                          type: string
                          description: >-
                            The time zone of the alarm time is consistent with
                            the time zone of the alarm rule.
                        alarmType:
                          type: string
                          description: Alarm Type
                        alarmValue:
                          type: integer
                          description: Alarm Value
                        createAt:
                          type: integer
                          description: Creation Time
                        level:
                          type: integer
                          description: 'Alarm level: 3 - Risk; 4 - Fault'
                        position:
                          type: string
                          description: Position
                        sn:
                          type: string
                          description: SN
                      required:
                        - alarmFrom
                        - alarmStart
                        - alarmType
                        - alarmValue
                        - createAt
                        - level
                        - position
                        - sn
                      x-apifox-orders:
                        - alarmFrom
                        - alarmStart
                        - alarmType
                        - alarmValue
                        - createAt
                        - level
                        - position
                        - sn
                required:
                  - items
                x-apifox-orders:
                  - items
              example:
                items:
                  - alarmFrom: '5'
                    alarmStart: '2024-05-07 20:55:24'
                    alarmType: '0'
                    alarmValue: 0
                    createAt: 1715086524688
                    level: 3
                    position: 010002000300054F0000
                    sn: 2YHA12705N3A10048835
                  - alarmFrom: '5'
                    alarmStart: '2024-05-07 20:55:24'
                    alarmType: '0'
                    alarmValue: 0
                    createAt: 1715086524688
                    level: 3
                    position: 010002000300054F0000
                    sn: 2YHA12705N3A10048835
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: VNNOXCare/Device Status Monitoring/Master Controller
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-181820143-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Operating Parameters Information

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/device-status-monitor/master-control/running/{sn}:
    get:
      summary: Operating Parameters Information
      deprecated: false
      description: >-

        :::tip

        1. This interface can be used for obtaining the monitoring information
        of the operating parameters of the current controller.

        2. Advanced interface.

        :::
      tags:
        - VNNOXCare/Device Status Monitoring/Master Controller
      parameters:
        - name: sn
          in: path
          description: Device SN
          required: true
          schema:
            type: string
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  basic:
                    type: object
                    properties:
                      availableMemory:
                        type: integer
                        description: Free Memory
                      availableStorageSpace:
                        type: integer
                        description: Free storage space
                      cpuUsageRate:
                        type: integer
                        description: CPU usage
                      playingStatus:
                        type: boolean
                        description: Playback status
                      resolutionRatio:
                        type: string
                        description: Resolution
                      screenStatus:
                        type: string
                        description: Screen status
                      sync:
                        type: 'null'
                        description: Synchronous playback
                      volume:
                        type: string
                        description: Volume
                    required:
                      - availableMemory
                      - availableStorageSpace
                      - cpuUsageRate
                      - playingStatus
                      - resolutionRatio
                      - screenStatus
                      - sync
                      - volume
                    x-apifox-orders:
                      - availableMemory
                      - availableStorageSpace
                      - cpuUsageRate
                      - playingStatus
                      - resolutionRatio
                      - screenStatus
                      - sync
                      - volume
                    description: Basic Information
                  lora:
                    type: object
                    properties:
                      enableLora:
                        type: boolean
                        description: On/Off status
                      existLora:
                        type: boolean
                        description: A 4G module is detected
                      funLora:
                        type: object
                        properties:
                          volumeEnable:
                            type: boolean
                          brightnessEnable:
                            type: boolean
                          environmentalMonitoring:
                            type: boolean
                          timeEnable:
                            type: boolean
                        required:
                          - volumeEnable
                          - brightnessEnable
                          - environmentalMonitoring
                          - timeEnable
                        x-apifox-orders:
                          - volumeEnable
                          - brightnessEnable
                          - environmentalMonitoring
                          - timeEnable
                        description: Functions using RF
                      groupId:
                        type: string
                        description: Group ID
                      mode:
                        type: string
                        description: Master/Slave device
                    required:
                      - enableLora
                      - existLora
                      - funLora
                      - groupId
                      - mode
                    x-apifox-orders:
                      - enableLora
                      - existLora
                      - funLora
                      - groupId
                      - mode
                    description: RF management
                  netWork:
                    type: object
                    properties:
                      dhcp:
                        type: array
                        items:
                          type: boolean
                        description: DHCP
                      dns:
                        type: array
                        items:
                          type: string
                        description: DNS
                      gateWay:
                        type: array
                        items:
                          type: string
                        description: GateWay
                      ip:
                        type: array
                        items:
                          type: string
                        description: IP
                      mask:
                        type: array
                        items:
                          type: string
                        description: Mask
                      networkType:
                        type: integer
                        description: >-
                          Network mode, network type (0: no network, 1: wired
                          network, 2: Wi-Fi, 3: 2G, 4: 3G, 5: 4G)
                    required:
                      - dhcp
                      - dns
                      - gateWay
                      - ip
                      - mask
                      - networkType
                    x-apifox-orders:
                      - dhcp
                      - dns
                      - gateWay
                      - ip
                      - mask
                      - networkType
                    description: Network parameters
                  rebotConfig:
                    type: object
                    properties:
                      enable:
                        type: array
                        items:
                          type: boolean
                        description: Enable
                      repetitionMethod:
                        type: array
                        items:
                          type: string
                        description: Repetition method
                      time:
                        type: array
                        items:
                          type: string
                        description: Time
                    required:
                      - enable
                      - repetitionMethod
                      - time
                    x-apifox-orders:
                      - enable
                      - repetitionMethod
                      - time
                    description: Restart configuration schedule
                  screen:
                    type: object
                    properties:
                      mode:
                        type: string
                        description: >-
                          Screen control mode (MANUALLY means to control the
                          screen manually. AUTO means to control the screen
                          automatically.)
                      state:
                        type: string
                        description: 'Restart adjustment mode ("OPEN": on, "CLOSE": off)'
                    required:
                      - mode
                      - state
                    x-apifox-orders:
                      - mode
                      - state
                    description: Screen
                  screenConfig:
                    type: object
                    properties:
                      action:
                        type: array
                        items:
                          type: string
                        description: 'Screen control (OPEN: on, CLOSE: off)'
                      enable:
                        type: array
                        items:
                          type: boolean
                        description: Enable
                      repetitionMethod:
                        type: array
                        items:
                          type: string
                        description: Repetition method
                      time:
                        type: array
                        items:
                          type: string
                        description: Time
                    required:
                      - action
                      - enable
                      - repetitionMethod
                      - time
                    x-apifox-orders:
                      - action
                      - enable
                      - repetitionMethod
                      - time
                    description: Screen configuration schedule
                  sensor:
                    type: object
                    properties:
                      sensorId:
                        type: array
                        items:
                          type: integer
                        description: Sensor
                      vendorAliasName:
                        type: array
                        items:
                          type: string
                        description: Manufacturer
                    required:
                      - sensorId
                      - vendorAliasName
                    x-apifox-orders:
                      - sensorId
                      - vendorAliasName
                    description: Sensor parameters
                  timeInfo:
                    type: object
                    properties:
                      time:
                        type: string
                        description: Time
                      timeSyncMode:
                        type: boolean
                        description: Time synchronization method
                      timeZone:
                        type: string
                        description: TimeZone
                    required:
                      - time
                      - timeSyncMode
                      - timeZone
                    x-apifox-orders:
                      - time
                      - timeSyncMode
                      - timeZone
                    description: TimeConfig
                  videoConfig:
                    type: object
                    properties:
                      enable:
                        type: array
                        items:
                          type: boolean
                        description: Enable
                      repetitionMethod:
                        type: array
                        items:
                          type: string
                        description: Repetition method
                      sourceTpye:
                        type: array
                        items:
                          type: integer
                        description: >-
                          Adjustment type (Internal: 0, HDMI: 1) (This field is
                          only available in manual mode.)
                      time:
                        type: array
                        items:
                          type: string
                        description: Time
                    required:
                      - enable
                      - repetitionMethod
                      - sourceTpye
                      - time
                    x-apifox-orders:
                      - enable
                      - repetitionMethod
                      - sourceTpye
                      - time
                    description: Video source configuration schedule
                  videoSource:
                    type: object
                    properties:
                      hdmiSource:
                        type: string
                        description: HDMI source
                      internalSource:
                        type: string
                        description: Internal source
                      offSet:
                        type: string
                        description: Output coordinates
                      videoMode:
                        type: integer
                        description: >-
                          Video source mode (HDMI preferred 0, manual 1,
                          scheduled 2)
                      videoSource:
                        type: integer
                        description: >-
                          Video source type (0-internal source, 1-external
                          source)
                    required:
                      - hdmiSource
                      - internalSource
                      - offSet
                      - videoMode
                      - videoSource
                    x-apifox-orders:
                      - hdmiSource
                      - internalSource
                      - offSet
                      - videoMode
                      - videoSource
                    description: Video source
                required:
                  - basic
                  - lora
                  - netWork
                  - rebotConfig
                  - screen
                  - screenConfig
                  - sensor
                  - timeInfo
                  - videoConfig
                  - videoSource
                x-apifox-orders:
                  - basic
                  - lora
                  - netWork
                  - rebotConfig
                  - screen
                  - screenConfig
                  - sensor
                  - timeInfo
                  - videoConfig
                  - videoSource
              example:
                basic:
                  availableMemory: 19
                  availableStorageSpace: 32
                  cpuUsageRate: 4
                  playingStatus: true
                  resolutionRatio: 1920*1080
                  screenStatus: OPEN
                  sync: null
                  volume: '75.0'
                lora:
                  enableLora: false
                  existLora: false
                  funLora:
                    volumeEnable: false
                    brightnessEnable: false
                    environmentalMonitoring: false
                    timeEnable: false
                  groupId: ''
                  mode: MASTER
                netWork:
                  dhcp:
                    - true
                  dns:
                    - 172.16.0.201
                    - 172.16.0.202
                  gateWay:
                    - 192.168.20.1
                  ip:
                    - 192.168.20.101
                  mask:
                    - 255.255.255.0
                  networkType: 0
                rebotConfig:
                  enable:
                    - true
                    - true
                  repetitionMethod:
                    - 0 35 23 23 5 ? 2024
                    - 0 0 21 ? * 1,5,6
                  time:
                    - 0 35 23 23 5 ? 2024
                    - 0 0 21 ? * 1,5,6
                screen:
                  mode: OPEN
                  state: MANUALLY
                screenConfig:
                  action:
                    - CLOSE
                    - OPEN
                  enable:
                    - true
                    - true
                  repetitionMethod:
                    - 0 26 23 ? * * *
                    - 0 0 0 ? * * *
                  time:
                    - 0 26 23 ? * * *
                    - 0 0 0 ? * * *
                sensor:
                  sensorId:
                    - 85
                    - 86
                  vendorAliasName:
                    - NovaStar
                    - NovaStar
                timeInfo:
                  time: '2024-05-07 13:52:43'
                  timeSyncMode: false
                  timeZone: Europe/London
                videoConfig:
                  enable:
                    - true
                    - true
                  repetitionMethod:
                    - 0 30 23 23 5 ? 2024
                    - 0 31 23 23 5 ? 2024
                  sourceTpye:
                    - 1
                    - 0
                  time:
                    - 0 30 23 23 5 ? 2024
                    - 0 31 23 23 5 ? 2024
                videoSource:
                  hdmiSource: 1920x1080p-60
                  internalSource: 1920x1080p-60
                  offSet: X=0;Y=0
                  videoMode: 2
                  videoSource: 0
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: VNNOXCare/Device Status Monitoring/Master Controller
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-188113043-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Alarm Infomation

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/device-status-monitor/smart-module/alarm/{sn}:
    get:
      summary: Alarm Infomation
      deprecated: false
      description: >-

        :::tip

        1. Through this interface, you can obtain the alarm information of the
        current device intelligent module.

        2. Advanced interface.

        :::
      tags:
        - VNNOXCare/Device Status Monitoring/Smart Module
      parameters:
        - name: sn
          in: path
          description: Device SN
          required: true
          schema:
            type: string
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  items:
                    type: array
                    items:
                      type: object
                      properties:
                        alarmFrom:
                          type: string
                          description: Source of alarm hardware device
                        alarmStart:
                          type: string
                          description: >-
                            The time zone of the alarm time is consistent with
                            the time zone of the alarm rule.
                        alarmType:
                          type: string
                          description: Alarm Type
                        alarmValue:
                          type: integer
                          description: Alarm Value
                        createAt:
                          type: integer
                          description: Creation Time
                        level:
                          type: integer
                          description: 'Alarm level: 3 - Risk; 4 - Fault'
                        position:
                          type: string
                          description: Position
                        sn:
                          type: string
                          description: SN
                      required:
                        - alarmFrom
                        - alarmStart
                        - alarmType
                        - alarmValue
                        - createAt
                        - level
                        - position
                        - sn
                      x-apifox-orders:
                        - alarmFrom
                        - alarmStart
                        - alarmType
                        - alarmValue
                        - createAt
                        - level
                        - position
                        - sn
                    description: Alarm List
                required:
                  - items
                x-apifox-orders:
                  - items
              example:
                items:
                  - alarmFrom: '5'
                    alarmStart: '2024-05-07 20:55:24'
                    alarmType: '0'
                    alarmValue: 0
                    createAt: 1715086524688
                    level: 3
                    position: 010002000300054F0000
                    sn: 2YHA12705N3A10048835
                  - alarmFrom: '5'
                    alarmStart: '2024-05-07 20:55:24'
                    alarmType: '0'
                    alarmValue: 0
                    createAt: 1715086524688
                    level: 3
                    position: 010002000300054F0000
                    sn: 2YHA12705N3A10048835
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: VNNOXCare/Device Status Monitoring/Smart Module
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-188114426-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Alarm Infomation

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/device-status-monitor/smart-module/alarm/{sn}:
    get:
      summary: Alarm Infomation
      deprecated: false
      description: >-

        :::tip

        1. Through this interface, you can obtain the alarm information of the
        current device intelligent module.

        2. Advanced interface.

        :::
      tags:
        - VNNOXCare/Device Status Monitoring/Smart Module
      parameters:
        - name: sn
          in: path
          description: Device SN
          required: true
          schema:
            type: string
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  items:
                    type: array
                    items:
                      type: object
                      properties:
                        alarmFrom:
                          type: string
                          description: Source of alarm hardware device
                        alarmStart:
                          type: string
                          description: >-
                            The time zone of the alarm time is consistent with
                            the time zone of the alarm rule.
                        alarmType:
                          type: string
                          description: Alarm Type
                        alarmValue:
                          type: integer
                          description: Alarm Value
                        createAt:
                          type: integer
                          description: Creation Time
                        level:
                          type: integer
                          description: 'Alarm level: 3 - Risk; 4 - Fault'
                        position:
                          type: string
                          description: Position
                        sn:
                          type: string
                          description: SN
                      required:
                        - alarmFrom
                        - alarmStart
                        - alarmType
                        - alarmValue
                        - createAt
                        - level
                        - position
                        - sn
                      x-apifox-orders:
                        - alarmFrom
                        - alarmStart
                        - alarmType
                        - alarmValue
                        - createAt
                        - level
                        - position
                        - sn
                    description: Alarm List
                required:
                  - items
                x-apifox-orders:
                  - items
              example:
                items:
                  - alarmFrom: '5'
                    alarmStart: '2024-05-07 20:55:24'
                    alarmType: '0'
                    alarmValue: 0
                    createAt: 1715086524688
                    level: 3
                    position: 010002000300054F0000
                    sn: 2YHA12705N3A10048835
                  - alarmFrom: '5'
                    alarmStart: '2024-05-07 20:55:24'
                    alarmType: '0'
                    alarmValue: 0
                    createAt: 1715086524688
                    level: 3
                    position: 010002000300054F0000
                    sn: 2YHA12705N3A10048835
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: VNNOXCare/Device Status Monitoring/Smart Module
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-188114426-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Monitoring Information

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/device-status-monitor/module/monitor/{sn}:
    get:
      summary: Monitoring Information
      deprecated: false
      description: >-

        :::tip

        1. This interface can be used for obtaining the basic monitoring
        information of the modules/cabinets loaded by the current device.

        2. Advanced interface.

        :::
      tags:
        - VNNOXCare/Device Status Monitoring/Module/Cabinet
      parameters:
        - name: sn
          in: path
          description: Device SN
          required: true
          schema:
            type: string
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  items:
                    type: array
                    items:
                      type: object
                      properties:
                        height:
                          type: integer
                          description: Module height
                        width:
                          type: integer
                          description: Module width
                        icType:
                          type: string
                          description: Driver IC type
                        lineDecodingIc:
                          type: string
                          description: Decoder IC type
                        scanNum:
                          type: string
                          description: Number of scans
                        sn:
                          type: string
                          description: Device SN
                      x-apifox-orders:
                        - height
                        - width
                        - icType
                        - lineDecodingIc
                        - scanNum
                        - sn
                    description: Module/Cabinet information list
                required:
                  - items
                x-apifox-orders:
                  - items
              example:
                items:
                  - height: 69
                    width: 41
                    icType: Chip_CommonBase
                    lineDecodingIc: '138'
                    scanNum: '84'
                    sn: 2YHA12706N1A10049941
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: VNNOXCare/Device Status Monitoring/Module/Cabinet
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-188115020-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```



# Alarm Information

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/device-status-monitor/monitor-card/alarm/{sn}:
    get:
      summary: Alarm Information
      deprecated: false
      description: >-

        :::tip

        1. This interface can be used for obtaining the alarm information of the
        monitoring cards loaded by the current device.

        2. Advanced interface.

        :::
      tags:
        - VNNOXCare/Device Status Monitoring/Monitoring Card
      parameters:
        - name: sn
          in: path
          description: ''
          required: true
          schema:
            type: string
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  items:
                    type: array
                    items:
                      type: object
                      properties:
                        alarmFrom:
                          type: string
                          description: Source of alarm hardware device
                        alarmType:
                          type: string
                          description: Alarm Type
                        alarmValue:
                          type: integer
                          description: Alarm Value
                        createAt:
                          type: integer
                          description: Creation Time
                        level:
                          type: integer
                          description: 'Alarm level: 3 - Risk; 4 - Fault'
                        position:
                          type: string
                          description: Location
                        sn:
                          type: string
                          description: Device SN
                        alarmTime:
                          type: string
                          description: >-
                            The time zone of the alarm time is consistent with
                            the time zone of the alarm rule.
                      required:
                        - alarmFrom
                        - alarmTime
                        - alarmType
                        - alarmValue
                        - createAt
                        - level
                        - position
                        - sn
                      x-apifox-orders:
                        - alarmFrom
                        - alarmTime
                        - alarmType
                        - alarmValue
                        - createAt
                        - level
                        - position
                        - sn
                required:
                  - items
                x-apifox-orders:
                  - items
              example:
                items:
                  - alarmFrom: '5'
                    alarmStart: '2024-05-07 20:55:24'
                    alarmType: '0'
                    alarmValue: 0
                    createAt: 1715086524688
                    level: 3
                    position: 010002000300054F0000
                    sn: 2YHA12705N3A10048835
                  - alarmFrom: '5'
                    alarmStart: '2024-05-07 20:55:24'
                    alarmType: '0'
                    alarmValue: 0
                    createAt: 1715086524688
                    level: 3
                    position: 010002000300054F0000
                    sn: 2YHA12705N3A10048835
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: VNNOXCare/Device Status Monitoring/Monitoring Card
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-341731719-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```

# Monitoring Information

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/device-status-monitor/monitor-card/monitor/{sn}:
    get:
      summary: Monitoring Information
      deprecated: false
      description: >-

        :::tip

        1. This is an interface used for obtaining the monitoring information
        from the monitoring card of the current device.

        2. This is an advanced interface.

        :::
      tags:
        - VNNOXCare/Device Status Monitoring/Monitoring Card
      parameters:
        - name: sn
          in: path
          description: ''
          required: true
          schema:
            type: string
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  items:
                    type: array
                    items:
                      type: object
                      properties:
                        cabinDoorStatus:
                          type: boolean
                          description: Cabinet door status
                        fansSpeed:
                          type: object
                          properties:
                            '0':
                              type: integer
                              description: Fan 1
                            '1':
                              type: integer
                              description: Fan 2
                            '2':
                              type: integer
                              description: Fan 3
                            '3':
                              type: integer
                              description: Fan 4
                            '4':
                              type: integer
                              description: Fan 5
                            '5':
                              type: integer
                              description: Fan 6
                            '6':
                              type: integer
                              description: Fan 7
                            '7':
                              type: integer
                              description: Fan 8
                            '8':
                              type: integer
                              description: Fan 9
                          required:
                            - '0'
                            - '1'
                            - '2'
                            - '3'
                            - '4'
                            - '5'
                            - '6'
                            - '7'
                            - '8'
                          x-apifox-orders:
                            - '0'
                            - '1'
                            - '2'
                            - '3'
                            - '4'
                            - '5'
                            - '6'
                            - '7'
                            - '8'
                          description: Fan speed
                        position:
                          type: string
                          description: Location
                        smokeStatus:
                          type: boolean
                          description: Smoke
                        sn:
                          type: string
                          description: Device SN
                        socketCableStatus:
                          type: array
                          items:
                            type: boolean
                          description: Flat cable status
                        status:
                          type: boolean
                          description: Working status
                        voltage:
                          type: object
                          properties:
                            '0':
                              type: number
                              description: Voltage 1
                            '1':
                              type: number
                              description: Voltage 2
                            '2':
                              type: number
                              description: Voltage 3
                            '3':
                              type: integer
                              description: Voltage 4
                            '4':
                              type: integer
                              description: Voltage 5
                            '5':
                              type: integer
                              description: Voltage 6
                            '6':
                              type: integer
                              description: Voltage 7
                            '7':
                              type: integer
                              description: Voltage 8
                            '8':
                              type: integer
                              description: Voltage 9
                          required:
                            - '0'
                            - '1'
                            - '2'
                            - '3'
                            - '4'
                            - '5'
                            - '6'
                            - '7'
                            - '8'
                          x-apifox-orders:
                            - '0'
                            - '1'
                            - '2'
                            - '3'
                            - '4'
                            - '5'
                            - '6'
                            - '7'
                            - '8'
                          description: Voltage
                      required:
                        - cabinDoorStatus
                        - fansSpeed
                        - position
                        - smokeStatus
                        - sn
                        - socketCableStatus
                        - status
                        - voltage
                      x-apifox-orders:
                        - cabinDoorStatus
                        - fansSpeed
                        - position
                        - smokeStatus
                        - sn
                        - socketCableStatus
                        - status
                        - voltage
                    description: Data list
                required:
                  - items
                x-apifox-orders:
                  - items
              example:
                items:
                  - cabinDoorStatus: true
                    fansSpeed:
                      '0': -255
                      '1': -255
                      '2': -255
                      '3': -255
                      '4': -255
                      '5': -255
                      '6': -255
                      '7': -255
                      '8': -255
                    position: '0200030005000600'
                    smokeStatus: true
                    sn: 002F2101000002DB
                    socketCableStatus:
                      - false
                    status: false
                    voltage:
                      '0': 3.9
                      '1': 3.3
                      '2': 3.3
                      '3': -255
                      '4': -255
                      '5': -255
                      '6': -255
                      '7': -255
                      '8': -255
                  - cabinDoorStatus: true
                    fansSpeed:
                      '0': -255
                      '1': -255
                      '2': -255
                      '3': -255
                      '4': -255
                      '5': -255
                      '6': -255
                      '7': -255
                      '8': -255
                    position: '0200030005010600'
                    smokeStatus: true
                    sn: 002F2101000002DB
                    socketCableStatus:
                      - false
                    status: false
                    voltage:
                      '0': 3.9
                      '1': 3.3
                      '2': 3.3
                      '3': -255
                      '4': -255
                      '5': -255
                      '6': -255
                      '7': -255
                      '8': -255
                  - cabinDoorStatus: true
                    fansSpeed:
                      '0': -255
                      '1': -255
                      '2': -255
                      '3': -255
                      '4': -255
                      '5': -255
                      '6': -255
                      '7': -255
                      '8': -255
                    position: '0200030005020600'
                    smokeStatus: true
                    sn: 002F2101000002DB
                    socketCableStatus:
                      - false
                    status: false
                    voltage:
                      '0': 3.9
                      '1': 3.4
                      '2': 3.3
                      '3': -255
                      '4': -255
                      '5': -255
                      '6': -255
                      '7': -255
                      '8': -255
                  - cabinDoorStatus: true
                    fansSpeed:
                      '0': -255
                      '1': -255
                      '2': -255
                      '3': -255
                      '4': -255
                      '5': -255
                      '6': -255
                      '7': -255
                      '8': -255
                    position: '0200030005030600'
                    smokeStatus: true
                    sn: 002F2101000002DB
                    socketCableStatus:
                      - false
                    status: false
                    voltage:
                      '0': 4
                      '1': 3.4
                      '2': 3.4
                      '3': -255
                      '4': -255
                      '5': -255
                      '6': -255
                      '7': -255
                      '8': -255
                  - cabinDoorStatus: true
                    fansSpeed:
                      '0': -255
                      '1': -255
                      '2': -255
                      '3': -255
                      '4': -255
                      '5': -255
                      '6': -255
                      '7': -255
                      '8': -255
                    position: '0200030005040600'
                    smokeStatus: true
                    sn: 002F2101000002DB
                    socketCableStatus:
                      - false
                    status: false
                    voltage:
                      '0': 3.8
                      '1': 3.3
                      '2': 3.3
                      '3': -255
                      '4': -255
                      '5': -255
                      '6': -255
                      '7': -255
                      '8': -255
                  - cabinDoorStatus: true
                    fansSpeed:
                      '0': -255
                      '1': -255
                      '2': -255
                      '3': -255
                      '4': -255
                      '5': -255
                      '6': -255
                      '7': -255
                      '8': -255
                    position: '0200030005050600'
                    smokeStatus: true
                    sn: 002F2101000002DB
                    socketCableStatus:
                      - false
                    status: false
                    voltage:
                      '0': 3.9
                      '1': 3.3
                      '2': 3.3
                      '3': -255
                      '4': -255
                      '5': -255
                      '6': -255
                      '7': -255
                      '8': -255
                  - cabinDoorStatus: true
                    fansSpeed:
                      '0': -255
                      '1': -255
                      '2': -255
                      '3': -255
                      '4': -255
                      '5': -255
                      '6': -255
                      '7': -255
                      '8': -255
                    position: '0200030005060600'
                    smokeStatus: true
                    sn: 002F2101000002DB
                    socketCableStatus:
                      - false
                    status: false
                    voltage:
                      '0': 4
                      '1': 3.3
                      '2': 3.3
                      '3': -255
                      '4': -255
                      '5': -255
                      '6': -255
                      '7': -255
                      '8': -255
                  - cabinDoorStatus: true
                    fansSpeed:
                      '0': -255
                      '1': -255
                      '2': -255
                      '3': -255
                      '4': -255
                      '5': -255
                      '6': -255
                      '7': -255
                      '8': -255
                    position: '0200030005070600'
                    smokeStatus: true
                    sn: 002F2101000002DB
                    socketCableStatus:
                      - false
                    status: false
                    voltage:
                      '0': 4
                      '1': 3.3
                      '2': 3.3
                      '3': -255
                      '4': -255
                      '5': -255
                      '6': -255
                      '7': -255
                      '8': -255
                  - cabinDoorStatus: true
                    fansSpeed:
                      '0': -255
                      '1': -255
                      '2': -255
                      '3': -255
                      '4': -255
                      '5': -255
                      '6': -255
                      '7': -255
                      '8': -255
                    position: '0200030005080600'
                    smokeStatus: true
                    sn: 002F2101000002DB
                    socketCableStatus:
                      - false
                    status: false
                    voltage:
                      '0': 4
                      '1': 3.3
                      '2': 3.3
                      '3': -255
                      '4': -255
                      '5': -255
                      '6': -255
                      '7': -255
                      '8': -255
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: VNNOXCare/Device Status Monitoring/Monitoring Card
      x-apifox-status: developing
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-198270522-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Camera configuration

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/device-status-monitor/camera:
    put:
      summary: Camera configuration
      deprecated: false
      description: |-

        :::tip
        1. Camera configuration information can be set through this interface.
        2. This is an advanced interface.
        :::
      tags:
        - VNNOXCare/Device Status Monitoring/Camera
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                enable:
                  type: integer
                  description: Whether on 1 On 0 Off
                id:
                  type: integer
                  description: Camera ID
                name:
                  type: string
                  description: Camera name
                sid:
                  type: integer
                  description: Device SID
              required:
                - enable
                - id
                - name
                - sid
              x-apifox-orders:
                - enable
                - id
                - name
                - sid
            example:
              enable: 0
              id: 0
              name: string
              sid: 0
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties: {}
                x-apifox-orders: []
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: VNNOXCare/Device Status Monitoring/Camera
      x-apifox-status: developing
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-293155189-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```



# The camera monitors the aggregated information

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/device-status-monitor/camera:
    post:
      summary: The camera monitors the aggregated information
      deprecated: false
      description: >-

        :::tip

        1. Through this interface, the monitoring picture information of the
        given display list can be obtained.

        2. This is an advanced interface.

        :::
      tags:
        - VNNOXCare/Device Status Monitoring/Camera
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                sidList:
                  type: array
                  items:
                    type: integer
                  description: Screen SID collection
                thumbnailWidth:
                  type: integer
                  description: Thumbnail width
                thumbnailHeight:
                  type: integer
                  description: Thumbnail height
              required:
                - sidList
                - thumbnailWidth
                - thumbnailHeight
              x-apifox-orders:
                - sidList
                - thumbnailWidth
                - thumbnailHeight
            example:
              sidList:
                - 0
              thumbnailWidth: 0
              thumbnailHeight: 0
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  noCameraSidList:
                    type: array
                    items:
                      type: string
                    description: List of camera-free sids
                  value:
                    type: array
                    items:
                      type: object
                      properties:
                        sid:
                          type: integer
                          description: Device SID
                        url:
                          type: array
                          items:
                            type: string
                          description: Image address list
                        thumbnailUrl:
                          type: array
                          items:
                            type: string
                          description: List of thumbnail image addresses
                        camera:
                          type: array
                          items:
                            type: object
                            properties:
                              id:
                                type: integer
                                description: Camera ID
                              name:
                                type: string
                                description: Camera name
                              enable:
                                type: integer
                                description: Whether on 1 On 0 Off
                              folderExist:
                                type: integer
                                description: >-
                                  Generated directory or not 1 Generated 0 Not
                                  generated
                              setting:
                                type: integer
                                description: >-
                                  Whether configuration 1 is configured 0 is not
                                  configured
                            x-apifox-orders:
                              - id
                              - name
                              - enable
                              - folderExist
                              - setting
                          description: Camera information
                      x-apifox-orders:
                        - sid
                        - url
                        - thumbnailUrl
                        - camera
                    description: The camera monitors the aggregated information
                required:
                  - noCameraSidList
                  - value
                x-apifox-orders:
                  - noCameraSidList
                  - value
              example:
                noCameraSidList:
                  - string
                value:
                  - sid: 0
                    url:
                      - string
                    thumbnailUrl:
                      - string
                    camera:
                      - id: 0
                        name: string
                        enable: 0
                        folderExist: 0
                        setting: 0
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: VNNOXCare/Device Status Monitoring/Camera
      x-apifox-status: developing
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-293155525-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```

# Brightness History

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/device-status-monitor/brightness/history:
    post:
      summary: Brightness History
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for obtaining the brightness adjustment
        history of the current device.

        2. The maximum range for brightness history search is 30 days.

        3. This is an advanced interface.

        :::
      tags:
        - VNNOXCare/Brightness Log
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                currentPage:
                  type: integer
                  description: Current page,1 by default.
                endTime:
                  type: string
                  description: End time,millisecond timestamp
                orderRule:
                  type: string
                  description: >-
                    Sorting rule,asc in ascending order and desc in reverse
                    order.
                pageSize:
                  type: integer
                  description: Quantity per page,10 by default.
                sn:
                  type: string
                  description: Device SN
                startTime:
                  type: string
                  description: Start time,millisecond timestamp
              required:
                - currentPage
                - endTime
                - orderRule
                - pageSize
                - sn
                - startTime
              x-apifox-orders:
                - currentPage
                - endTime
                - orderRule
                - pageSize
                - sn
                - startTime
            example:
              currentPage: 0
              endTime: string
              orderRule: string
              pageSize: 0
              sn: string
              startTime: string
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  currentPage:
                    type: integer
                    description: Current page
                  data:
                    type: array
                    items:
                      type: object
                      properties:
                        brightType:
                          type: integer
                          description: >-
                            Adjustment type 0-Manual 1-Scheduled 2-Automatic
                            5-Failed to read the light sensor data
                        brightValue:
                          type: integer
                          description: Brightness percentage
                        recordTime:
                          type: string
                          description: Adjustment time
                        result:
                          type: boolean
                          description: Adjustment result
                        sn:
                          type: string
                          description: Device SN
                        timezone:
                          type: integer
                          description: Time zone
                      required:
                        - brightType
                        - brightValue
                        - recordTime
                        - result
                        - sn
                        - timezone
                      x-apifox-orders:
                        - brightType
                        - brightValue
                        - recordTime
                        - result
                        - sn
                        - timezone
                    description: Data
                  total:
                    type: integer
                    description: Total number
                  totalPage:
                    type: integer
                    description: Total number of pages
                required:
                  - currentPage
                  - data
                  - total
                  - totalPage
                x-apifox-orders:
                  - currentPage
                  - data
                  - total
                  - totalPage
              example:
                currentPage: 0
                endTime: string
                orderRule: string
                pageSize: 0
                sn: string
                startTime: string
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: VNNOXCare/Brightness Log
      x-apifox-status: developing
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-198267779-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Obtaining User List

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/integration/account/list:
    get:
      summary: Obtaining User List
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for obtaining the users (including the
        sub-users) who have access to the system.

        2. Advanced interface.

        :::
      tags:
        - Others/Third-Party System Authorization
      parameters: []
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  username:
                    type: string
                    description: User name
                  isOrg:
                    type: boolean
                    description: Whether the user is the organization administrator
                  sysPermissions:
                    type: string
                    description: >-
                      System permissions: pro-VNNOX AD permissions，lite-VNNOX
                      Standard permissions
                x-apifox-orders:
                  - username
                  - isOrg
                  - sysPermissions
                required:
                  - username
                  - isOrg
                  - sysPermissions
              example:
                - username: myfdev
                  isOrg: true
                  sysPermissions:
                    - pro
                    - lite
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: Others/Third-Party System Authorization
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-186409405-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Obtaining Login URL

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths:
  /v2/integration/vnnox-access-url:
    post:
      summary: Obtaining Login URL
      deprecated: false
      description: >-

        :::tip

        1. This interface is used for obtaining the URL to access the VNNOX
        system.

        2. This interface cannot be called by sub-users.

        3. Advanced interface.

        :::
      tags:
        - Others/Third-Party System Authorization
      parameters: []
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                username:
                  type: string
                  description: User name to access the system
                lang:
                  type: string
                  description: >-
                    Display language after the user accesses the system: zh-CN:
                    Simplified Chinese en: English jp: Japanese
                sys:
                  type: string
                  description: >-
                    Subsystem displayed after the user access VNNOX. VNNOX AD is
                    accessed by default if this is not specified. pro: VNNOX AD
                    lite: VNNOX Standard
              x-apifox-orders:
                - username
                - lang
                - sys
              required:
                - username
                - lang
                - sys
            example:
              username: myfdev
              lang: zh-CN
              sys: pro
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  url:
                    type: string
                    description: URL to redirect to VNNOX
                  expire:
                    type: integer
                    description: Valid time of the URL (second)
                x-apifox-orders:
                  - url
                  - expire
                required:
                  - url
                  - expire
              example:
                url: >-
                  http://127.0.0.1:92/enterVnnox/e25e34963a5bfadc223e573685351585
                expire: 3600
          headers: {}
          x-apifox-name: 成功
      security: []
      x-apifox-folder: Others/Third-Party System Authorization
      x-apifox-status: released
      x-run-in-apifox: https://app.apifox.com/web/project/4577789/apis/api-186409414-run
components:
  schemas: {}
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```

SCHEMAS:
# Scheduled plan item

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths: {}
components:
  schemas:
    Scheduled plan item:
      type: object
      properties:
        startDate:
          type: string
          description: >-
            The start date of the scheduled plan, in the format of YYYY-MM-DD
            (e.g. 2023-01-01)
        endDate:
          type: string
          description: >-
            The end date of the scheduled plan, in the format of YYYY-MM-DD
            (e.g. 2023-01-01)
        weekDays:
          type: array
          items:
            type: integer
          description: >-
            Scheduled week configuration, effective elements range 0-6:
            0-Sunday, 1-Monday, 2-Tuesday, 3-Wednesday, 4-Thursday, 5-Friday,
            6-Saturday. If any element exists, the schedule takes effect on the
            day, and the default or empty collection indicates that it is
            executed every day within the validity period. And if the parameter
            is not provided,  it indicates that the plan will executed daily.
        execTime:
          type: string
          description: >-
            The scheduled execution time is in 24-hour HH:MM:SS format (for
            example, 21:00:00). Note: The player triggers the execution plan
            based on the player local time.
      x-apifox-orders:
        - startDate
        - endDate
        - weekDays
        - execTime
      required:
        - startDate
        - endDate
        - execTime
      x-apifox-folder: ''
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```

# Player Common Solution

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths: {}
components:
  schemas:
    Player Common Solution:
      type: object
      properties:
        playerIds:
          type: array
          items:
            type: string
            description: player IDs
          description: >-
            A collection of player IDs to be handled. At most 100 player IDs can
            be handled simultaneously.
        schedule:
          type: object
          properties:
            startDate:
              type: string
              description: 'Playback start date, example: 2020-04-12.'
            endDate:
              type: string
              description: 'Playback end date, example: 2020-12-22.'
            plans:
              type: array
              items:
                type: object
                properties:
                  weekDays:
                    type: array
                    items:
                      type: integer
                      description: Playback days of the week
                    description: >-
                      Playback days of the week, 0-Sunday, 1-Monday, 2-Tuesday,
                      3-Wednesday, 4-Thursday, 5-Friday, 6-Saturday.
                  startTime:
                    type: string
                    description: 'Start time of the day, example: 08:00.'
                  endTime:
                    type: string
                    description: End time of the day, example:18:00.
                x-apifox-orders:
                  - weekDays
                  - startTime
                  - endTime
                required:
                  - weekDays
                  - endTime
                  - startTime
                description: Specific playback plan
              description: Specific playback plan
          x-apifox-orders:
            - startDate
            - endDate
            - plans
          description: >-
            Playback schedule. If this is empty, the playback will be repeated
            24 hours.
          required:
            - plans
            - startDate
            - endDate
        noticeUrl:
          type: string
          description: >-
            Solution download progress notification interface. This interface is
            used for notifying the customer of the solution download progress.
            The response time of the interface cannot exceed 3s.
        pages:
          type: array
          items:
            type: object
            properties:
              name:
                type: string
                description: Page name
              schedules:
                type: array
                items:
                  type: object
                  properties:
                    startDate:
                      type: string
                      description: 'Playback start date, example: 2020-04-12.'
                    endDate:
                      type: string
                      description: 'Playback end date, example: 2020-12-22.'
                    plans:
                      type: array
                      items:
                        type: object
                        properties:
                          weekDays:
                            type: string
                            description: >-
                              Playback days of the week, 0-Sunday, 1-Monday,
                              2-Tuesday, 3-Wednesday, 4-Thursday, 5-Friday,
                              6-Saturday.
                          startTime:
                            type: string
                            description: 'Start time of the day, example: 08:00.'
                          endTime:
                            type: string
                            description: End time of the day, example:18:00.
                        x-apifox-orders:
                          - weekDays
                          - startTime
                          - endTime
                        required:
                          - weekDays
                          - endTime
                          - startTime
                        description: Specific playback plan
                      description: Specific playback plan
                  x-apifox-orders:
                    - startDate
                    - endDate
                    - plans
                  required:
                    - startDate
                    - plans
                    - endDate
                  description: Playback Schedule
                description: >-
                  It denotes the schedule list for this page. If it is empty,
                  the page will be looped continuously for 24 hours. However, if
                  both the program and page schedules are set, the page will
                  play only during the times that overlap between the two
                  schedules.
              widgets:
                type: array
                items:
                  anyOf:
                    - description: 'Widget type: PICTURE | GIF | VIDEO'
                      type: object
                      properties:
                        name:
                          type: string
                          description: >-
                            Widget name. This is used for querying logs. If this
                            is empty, it will be difficult to distinguish when
                            you query logs.
                        type:
                          type: string
                          description: Widget type. PICTURE | GIF | VIDEO.
                        md5:
                          type: string
                          description: >-
                            MD5 value of the image and video centent, it must be
                            lowercase.
                        size:
                          type: number
                          description: Image or video size (byte)
                        duration:
                          type: number
                          description: >-
                            Playback duration of a widget which is accurate to
                            millisecond.
                        url:
                          type: string
                          description: >-
                            Image\GIF\Video\RSS\Web page\Streaming media URL,
                            You are required to verify the URL validity.
                        zIndex:
                          type: integer
                          description: >-
                            Layer order of a widget. The greater the number, the
                            upper the layer. It defaults to 0.
                        layout:
                          type: object
                          properties:
                            x:
                              type: string
                              description: >-
                                Position of a widget relative to the left side
                                of the page, example: 10%.
                            'y':
                              type: string
                              description: >-
                                Position of a widget relative to the top of the
                                page, example: 10%.
                            width:
                              type: string
                              description: >-
                                Widget width relative to the page, example:
                                100%.
                            height:
                              type: string
                              description: >-
                                Widget height relative to the page, example:
                                100%.
                          x-apifox-orders:
                            - x
                            - 'y'
                            - width
                            - height
                          description: Widget position on a page
                          required:
                            - x
                            - height
                            - width
                            - 'y'
                        inAnimation:
                          type: object
                          properties:
                            type:
                              type: string
                              description: >-
                                Effect type NONE-No effect RANDOM-Random For
                                others
                            duration:
                              type: number
                              description: Effect duration (millisecond)
                          x-apifox-orders:
                            - type
                            - duration
                          required:
                            - type
                            - duration
                          description: Entrance effect of a widget. No effect by default.
                      x-apifox-refs: {}
                      x-apifox-orders:
                        - zIndex
                        - name
                        - type
                        - md5
                        - size
                        - duration
                        - url
                        - layout
                        - inAnimation
                      required:
                        - layout
                        - url
                        - duration
                        - size
                        - md5
                        - type
                    - type: object
                      properties:
                        name:
                          type: string
                          description: >-
                            Widget name. This is used for querying logs. If this
                            is empty, it will be difficult to distinguish when
                            you query logs.
                        type:
                          type: string
                          description: 'Widget type: WEATHER. '
                        duration:
                          type: number
                          description: >-
                            Playback duration of a widget which is accurate to
                            millisecond.
                        zIndex:
                          type: integer
                          description: >-
                            Layer order of a widget. The greater the number, the
                            upper the layer. It defaults to 0.
                        layout:
                          type: object
                          properties:
                            x:
                              type: string
                              description: >-
                                Position of a widget relative to the left side
                                of the page, example: 10%.
                            'y':
                              type: string
                              description: >-
                                Position of a widget relative to the top of the
                                page, example: 10%.
                            width:
                              type: string
                              description: >-
                                Widget width relative to the page, example:
                                100%.
                            height:
                              type: string
                              description: >-
                                Widget height relative to the page, example:
                                100%.
                          x-apifox-orders:
                            - x
                            - 'y'
                            - width
                            - height
                          description: Widget position on a page
                          required:
                            - x
                            - height
                            - width
                            - 'y'
                        inAnimation:
                          type: object
                          properties:
                            type:
                              type: string
                              description: >-
                                Effect type NONE-No effect RANDOM-Random For
                                others
                            duration:
                              type: number
                              description: Effect duration (millisecond)
                          x-apifox-orders:
                            - type
                            - duration
                          required:
                            - type
                            - duration
                          description: Entrance effect of a widget. No effect by default.
                        address:
                          type: string
                        latitude:
                          type: number
                        longitude:
                          type: number
                        width:
                          type: number
                          description: >-
                            The relative width of the weather widget component;
                            The effect of the display is related to the layout
                            parameter, if the screen display of the created 
                            widget is incomplete, please increase this value
                            appropriately.
                        height:
                          type: number
                          description: >-
                            The relative height of the weather widget component;
                            The effect of the display is related to the layout
                            parameter, if the screen display of the created 
                            widget is incomplete, please increase this value
                            appropriately.
                        refreshPeriod:
                          type: integer
                          description: Refresh cycle in milliseconds
                        fontSize:
                          type: integer
                        italic:
                          type: boolean
                        underline:
                          type: boolean
                        color:
                          type: string
                          description: 'Font color, format example: ''#00FFD4'' (Cyan)'
                        tempUnit:
                          type: integer
                          description: 'Temperature unit type (0: Celsius | 1: Fahrenheit)'
                        unitSymbol:
                          type: string
                          description: >-
                            Unit display type:  0=° (generic) or 1=℃/℉
                            (specific); when using Fahrenheit (tempUnit=1), only
                            1 is allowed.
                        weatherEnable:
                          type: boolean
                          description: weather element toggle
                        tempEnable:
                          type: boolean
                          description: temperature element toggle
                        windEnable:
                          type: boolean
                          description: wind element toggle
                        humidEnable:
                          type: boolean
                          description: humidity element toggle
                        currentTempEnable:
                          type: boolean
                          description: real-time temperature element toggle
                        isShowInOneLine:
                          type: boolean
                          description: single line mode toggle
                      x-apifox-refs: {}
                      x-apifox-orders:
                        - zIndex
                        - name
                        - type
                        - duration
                        - layout
                        - inAnimation
                        - address
                        - latitude
                        - longitude
                        - width
                        - height
                        - refreshPeriod
                        - fontSize
                        - italic
                        - underline
                        - color
                        - tempUnit
                        - unitSymbol
                        - weatherEnable
                        - tempEnable
                        - windEnable
                        - humidEnable
                        - currentTempEnable
                        - isShowInOneLine
                      required:
                        - layout
                        - duration
                        - type
                        - address
                        - weatherEnable
                        - unitSymbol
                        - tempUnit
                        - color
                        - underline
                        - italic
                        - fontSize
                        - refreshPeriod
                        - height
                        - width
                        - longitude
                        - latitude
                        - humidEnable
                        - windEnable
                        - tempEnable
                        - isShowInOneLine
                        - currentTempEnable
                      description: 'Widget type: WEATHER'
                    - type: object
                      properties:
                        zIndex:
                          type: integer
                          description: >-
                            Layer order of a widget. The greater the number, the
                            upper the layer. It defaults to 0.
                        type:
                          type: string
                          description: >-
                            Widget type: RT_MEDIA (Enviromental Monitoring
                            widget)
                        fontFamily:
                          type: string
                          description: |-
                            Text Font (Default: Arial)
                            Supported Player Fonts:
                            SimSun
                            Microsoft YaHei
                            KaiTi
                            Arial
                            Wingdings 2
                            Calibri
                        labelFontSize:
                          type: integer
                          description: Label Font Size. Range:9~256
                        valueFontSize:
                          type: integer
                          description: Data Font Size. Range:9~256
                        unitFontSize:
                          type: integer
                          description: Unit Font Size. Range:9~256
                        bold:
                          type: boolean
                          description: 'Bold (Default: false)'
                        italic:
                          type: boolean
                          description: 'Italic (Default: false)'
                        underline:
                          type: boolean
                          description: 'Underline (Default: false)'
                        textColor:
                          type: string
                          description: 'Font Color: #00FFD4'
                        refreshPeriod:
                          type: integer
                          description: 'Data Refresh Interval (Unit: ms)'
                        style:
                          type: integer
                          description: >-
                            Playback Style (Default: 1, Options: 1-Style1,
                            2-Style2, 3-Style3, 4-Style4)
                        playMode:
                          type: string
                          description: 'Play Mode，(Default: STATIC, Options: STATI, SCROLL)'
                        seedByPixelEnable:
                          type: boolean
                          description: 'Enable Pixel Scrolling (true: Pixel, false: Gear)'
                        speedGear:
                          type: integer
                          description: >-
                            Gear Level (Effective when playMode=Scroll AND
                            seedByPixelEnable=false, Range: 1~10)
                        speedPixel:
                          type: integer
                          description: >-
                            Pixel Value (Effective when playMode=Scroll AND
                            seedByPixelEnable=true, Range: 10~500px)
                        customLabel:
                          type: object
                          properties:
                            NOI:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: Display name
                                enable:
                                  type: boolean
                                  description: Enable or not，false-not，true-enable
                                unit:
                                  type: string
                                  description: >-
                                    unit，Recommended Value：ions/cm³，Supports
                                    customization
                              required:
                                - name
                                - enable
                                - unit
                              x-apifox-orders:
                                - name
                                - enable
                                - unit
                              description: Negative oxygen ions
                            UVR:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: Display name
                                enable:
                                  type: boolean
                                  description: Enable or not，false-not，true-enable
                                unit:
                                  type: string
                                  description: >-
                                    unit，Recommended Value：nm，Supports
                                    customization
                              required:
                                - name
                                - enable
                                - unit
                              x-apifox-orders:
                                - name
                                - enable
                                - unit
                              description: UV Radiation
                            airHumidity:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: Display name
                                enable:
                                  type: boolean
                                  description: Enable or not，false-not，true-enable
                                unit:
                                  type: string
                                  description: >-
                                    Unit，Recommended Value：%RH，Supports
                                    customization
                              required:
                                - name
                                - enable
                                - unit
                              x-apifox-orders:
                                - name
                                - enable
                                - unit
                              description: airHumidity
                            airPressure:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: 气压显示名称
                                enable:
                                  type: boolean
                                  description: 是否显示，false-不显示，true-显示
                                type:
                                  type: integer
                                  description: >-
                                    气压数据类型，枚举值：0-KPa 1-bar 2-atm 3-mmHg 4-Torr
                                    5-kgf/cm2，6-hpa，默认：0
                                unit:
                                  type: string
                                  description: 温度单位，请对应数据类型传参，例如，type=0，unitName=KPa
                              required:
                                - name
                                - enable
                                - type
                                - unit
                              x-apifox-orders:
                                - name
                                - enable
                                - type
                                - unit
                              description: airPressure
                            ambiantLight:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: Display name
                                enable:
                                  type: boolean
                                  description: Enable or not，false-not，true-enable
                                unit:
                                  type: string
                                  description: >-
                                    Unit，Recommended Value：Lux，Supports
                                    customization
                              required:
                                - name
                                - enable
                                - unit
                              x-apifox-orders:
                                - name
                                - enable
                                - unit
                              description: 'Illuminance '
                            coII:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: Display name
                                enable:
                                  type: boolean
                                  description: Enable or not，false-not，true-enable
                                unit:
                                  type: string
                                  description: >-
                                    Unit，Recommended Value：ppm，Supports
                                    customization
                              required:
                                - name
                                - enable
                                - unit
                              x-apifox-orders:
                                - name
                                - enable
                                - unit
                              description: Carbon Dioxide
                            noise:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: Display name
                                enable:
                                  type: boolean
                                  description: Enable or not，false-not，true-enable
                                unit:
                                  type: string
                                  description: >-
                                    Unit，Recommended Value：db，Supports
                                    customization
                              required:
                                - name
                                - enable
                                - unit
                              x-apifox-orders:
                                - name
                                - enable
                                - unit
                              description: noise
                            pmC:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: Display name
                                enable:
                                  type: boolean
                                  description: Enable or not，false-not，true-enable
                                unit:
                                  type: string
                                  description: >-
                                    Unit，Recommended Value：μg/m3，Supports
                                    customization
                              required:
                                - name
                                - enable
                                - unit
                              x-apifox-orders:
                                - name
                                - enable
                                - unit
                              description: PM100
                            pmIIV:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: Display name
                                enable:
                                  type: boolean
                                  description: Enable or not，false-not，true-enable
                                unit:
                                  type: string
                                  description: >-
                                    Unit，Recommended Value：μg/m3，Supports
                                    customization
                              required:
                                - name
                                - enable
                                - unit
                              x-apifox-orders:
                                - name
                                - enable
                                - unit
                              description: PM2.5
                            pmX:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: Display name
                                enable:
                                  type: boolean
                                  description: Enable or not，false-not，true-enable
                                unit:
                                  type: string
                                  description: >-
                                    Unit，Recommended Value：μg/m3，Supports
                                    customization
                              required:
                                - name
                                - enable
                                - unit
                              x-apifox-orders:
                                - name
                                - enable
                                - unit
                              description: PM10
                            rainfall:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: Display name
                                enable:
                                  type: boolean
                                  description: Enable or not，false-not，true-enable
                                unit:
                                  type: string
                                  description: >-
                                    Unit，Recommended Value：mm，Supports
                                    customization
                              required:
                                - name
                                - enable
                                - unit
                              x-apifox-orders:
                                - name
                                - enable
                                - unit
                              description: Rainfall
                            snowfall:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: Display name
                                enable:
                                  type: boolean
                                  description: Enable or not，false-not，true-enable
                                unit:
                                  type: string
                                  description: >-
                                    Unit，Recommended Value：mm，Supports
                                    customization
                              required:
                                - name
                                - enable
                                - unit
                              x-apifox-orders:
                                - name
                                - enable
                                - unit
                              description: Snowfall
                            soilMoisture:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: Display name
                                enable:
                                  type: boolean
                                  description: Enable or not，false-not，true-enable
                                unit:
                                  type: string
                                  description: >-
                                    Unit，Recommended Value：RH，Supports
                                    customization
                              required:
                                - name
                                - enable
                                - unit
                              x-apifox-orders:
                                - name
                                - enable
                                - unit
                              description: Soil Moisture
                            soilPH:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: Display name
                                enable:
                                  type: boolean
                                  description: Enable or not，false-not，true-enable
                                unit:
                                  type: string
                                  description: >-
                                    Unit，Recommended Value：pH，Supports
                                    customization
                              required:
                                - name
                                - enable
                                - unit
                              x-apifox-orders:
                                - name
                                - enable
                                - unit
                              description: Soil pH
                            soilTemperature:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: Display name
                                enable:
                                  type: boolean
                                  description: Enable or not，false-not，true-enable
                                type:
                                  type: integer
                                  description: >-
                                    Soil Temperature Data Type, 0: Celsius (°C)
                                    (default)  1: Fahrenheit (°F)
                                unit:
                                  type: string
                                  description: >-
                                    Unit，Please pass parameters corresponding to
                                    the data type，For example, type=0, unit=℃
                              required:
                                - name
                                - enable
                                - type
                                - unit
                              x-apifox-orders:
                                - name
                                - enable
                                - type
                                - unit
                              description: Soil Temperature
                            sunshineDuration:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: Display name
                                enable:
                                  type: boolean
                                  description: Enable or not，false-not，true-enable
                                unit:
                                  type: string
                                  description: >-
                                    Unit，Recommended Value：W/m2，Supports
                                    customization
                              required:
                                - name
                                - enable
                                - unit
                              x-apifox-orders:
                                - name
                                - enable
                                - unit
                              description: Sunshine Duration
                            temperature:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: Display name
                                enable:
                                  type: boolean
                                  description: Enable or not，false-not，true-enable
                                type:
                                  type: integer
                                  description: >-
                                    Temperature Data Type, 0: Celsius (°C)
                                    (default)  1: Fahrenheit (°F)
                                unit:
                                  type: string
                                  description: >-
                                    Unit，Please pass parameters corresponding to
                                    the data type，For example, type=0, unit=℃
                                tempCompensate:
                                  type: integer
                                  description: Compensate，Default：0，Range：-50~50
                              required:
                                - name
                                - enable
                                - type
                                - unit
                                - tempCompensate
                              x-apifox-orders:
                                - name
                                - enable
                                - type
                                - unit
                                - tempCompensate
                              description: temperature
                            windDirection:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: Display name
                                enable:
                                  type: boolean
                                  description: Enable or not，false-not，true-enable
                                templates:
                                  type: array
                                  items:
                                    type: string
                                  description: >-
                                    this field is mandatory when wind direction
                                    data is available. Values must strictly
                                    follow the predefined order below:
                                    North,Northeast,East,Southeast,South,Southwest,West,Northwest
                              required:
                                - name
                                - enable
                                - templates
                              x-apifox-orders:
                                - name
                                - enable
                                - templates
                              description: Wind Direction
                            windSpeed:
                              type: object
                              properties:
                                name:
                                  type: string
                                  description: Display name
                                enable:
                                  type: boolean
                                  description: Enable or not，false-not，true-enable
                                type:
                                  type: integer
                                  description: >-
                                    Wind Speed Data Type，0: Kilometers Per Hour,
                                    1: Meters Per Second, 2: level
                                unit:
                                  type: string
                                  description: >-
                                    Unit，Please pass parameters corresponding to
                                    the data type，For example, type=0, unit=km/h
                              required:
                                - name
                                - enable
                                - type
                                - unit
                              x-apifox-orders:
                                - name
                                - enable
                                - type
                                - unit
                              description: Wind Speed
                          required:
                            - NOI
                            - UVR
                            - airHumidity
                            - airPressure
                            - ambiantLight
                            - coII
                            - noise
                            - pmC
                            - pmIIV
                            - pmX
                            - rainfall
                            - snowfall
                            - soilMoisture
                            - soilPH
                            - soilTemperature
                            - sunshineDuration
                            - temperature
                            - windDirection
                            - windSpeed
                          x-apifox-orders:
                            - NOI
                            - UVR
                            - airHumidity
                            - airPressure
                            - ambiantLight
                            - coII
                            - noise
                            - pmC
                            - pmIIV
                            - pmX
                            - rainfall
                            - snowfall
                            - soilMoisture
                            - soilPH
                            - soilTemperature
                            - sunshineDuration
                            - temperature
                            - windDirection
                            - windSpeed
                          description: Item Attribute Structure
                        duration:
                          type: integer
                          description: Effect duration (millisecond)
                        layout:
                          type: object
                          properties:
                            x:
                              type: string
                              description: >-
                                Position of a widget relative to the left side
                                of the page, example: 10%.
                            'y':
                              type: string
                              description: >-
                                Position of a widget relative to the top of the
                                page, example: 10%.
                            width:
                              type: string
                              description: >-
                                Widget width relative to the page, example:
                                100%.
                            height:
                              type: string
                              description: >-
                                Widget height relative to the page, example:
                                100%.
                          required:
                            - x
                            - 'y'
                            - width
                            - height
                          x-apifox-orders:
                            - x
                            - 'y'
                            - width
                            - height
                          description: Widget position on a page
                      x-apifox-refs: {}
                      x-apifox-orders:
                        - zIndex
                        - type
                        - fontFamily
                        - labelFontSize
                        - valueFontSize
                        - unitFontSize
                        - bold
                        - italic
                        - underline
                        - textColor
                        - refreshPeriod
                        - style
                        - playMode
                        - seedByPixelEnable
                        - speedGear
                        - speedPixel
                        - customLabel
                        - duration
                        - layout
                      required:
                        - zIndex
                        - type
                        - fontFamily
                        - labelFontSize
                        - valueFontSize
                        - unitFontSize
                        - bold
                        - italic
                        - underline
                        - textColor
                        - refreshPeriod
                        - style
                        - playMode
                        - seedByPixelEnable
                        - speedGear
                        - speedPixel
                        - customLabel
                        - duration
                        - layout
                      description: 'Widget type: RT_MEDIA (Enviromental Monitoring widget)'
                    - type: object
                      properties:
                        type:
                          type: string
                          description: 'Widget type: DRAWN_DIGITAL_CLOCK'
                        zIndex:
                          type: integer
                          description: >-
                            Component overlay order, the larger the number, the
                            upper the layer, default 0
                        layout:
                          type: object
                          properties:
                            x:
                              type: string
                              description: >-
                                The component's position relative to the left
                                side of the page, such as: 10%
                            'y':
                              type: string
                              description: >-
                                The component's position relative to the top of
                                the page, such as: 10%
                            width:
                              type: string
                              description: >-
                                The width of the component relative to the page,
                                such as: 100%
                            height:
                              type: string
                              description: >-
                                The height of the component relative to the
                                page, such as: 100%
                          x-apifox-orders:
                            - x
                            - 'y'
                            - width
                            - height
                          description: The location of the component on the page
                          required:
                            - x
                            - height
                            - width
                            - 'y'
                        zone:
                          type: string
                          description: >-
                            IANA Time Zone Identifier, e.g. 
                            "America/Los_Angeles"
                        gmt:
                          type: string
                          description: GMT Time Zone, e.g. "GMT-08:00"
                        regular:
                          type: string
                          description: >-
                            The display rules of the digital clock and the
                            placeholder are defined as follows: 

                            \$dd: represents the day;

                            \$MM: Represents the month;

                            \$yyyy  Represents the year in 4 digits, while \$yy
                            in 2 digits;

                            \$E: A placeholder for the day of the week;

                            \$HH: hour, in 24-hour format;

                            \$hh: hour, in 12-hour format;

                            \$mm: minutes;

                            \$ss: seconds;

                            \$N: morning or afternoon;

                            \\n: Lines are wrapped;
                        weekTemplates:
                          type: array
                          items:
                            type: string
                          description: >-
                            Display template for the week, seven data items,
                            representing Monday to Sunday respectively
                        suffixTemplates:
                          type: array
                          items:
                            type: string
                          description: Display template for morning and afternoon
                        textColor:
                          type: string
                          description: >-
                            The foreground color of the text, the default
                            #FF0000
                        fontSize:
                          type: integer
                          description: Font size by pixels, the default is 16
                        fontFamily:
                          type: array
                          items:
                            type: string
                          description: >-
                            Font type array, when there are multiple fonts, the
                            first one takes precedence, if the first one is
                            invalid, then the following font is taken in turn,
                            if there is no such font, the system default one is
                            used. For example: ["Times","Georia","New York"]
                        fontStyle:
                          type: string
                          description: 'Font type: 1.BOLD, 2.NORMAL, 3.ITALIC, 4.BOLD_ITALIC'
                        fontIsUnderline:
                          type: boolean
                          description: Is the font underlined or not
                        backgroundColor:
                          type: string
                          description: 'Background color, default #00FFFFFF'
                        shadowEnable:
                          type: boolean
                          description: Whether to enable shadows, default is false
                        shadowRadius:
                          type: integer
                          description: Shadow radius size in pixels
                        shadowDx:
                          type: integer
                          description: Shadow offset of the x-axis
                        shadowDy:
                          type: integer
                          description: Shadow offset of the y-axis
                        shadowColor:
                          type: string
                          description: Shadow color
                      x-apifox-refs: {}
                      x-apifox-orders:
                        - zIndex
                        - type
                        - zone
                        - gmt
                        - regular
                        - weekTemplates
                        - suffixTemplates
                        - textColor
                        - fontSize
                        - fontFamily
                        - fontStyle
                        - fontIsUnderline
                        - backgroundColor
                        - shadowEnable
                        - shadowRadius
                        - shadowDx
                        - shadowDy
                        - shadowColor
                        - layout
                      required:
                        - layout
                        - suffixTemplates
                        - weekTemplates
                        - regular
                        - gmt
                        - type
                        - fontStyle
                        - fontIsUnderline
                        - backgroundColor
                        - shadowEnable
                        - shadowDy
                        - shadowDx
                        - shadowRadius
                        - shadowColor
                        - zone
                        - textColor
                        - fontSize
                        - fontFamily
                        - zIndex
                      description: 'Widget type: DRAWN_DIGITAL_CLOCK'
                    - type: object
                      properties: {}
                      x-apifox-orders: []
                      description: Other widget type will coming soon
                description: >-
                  Widgets on a page.  

                  Supported Widget Types: (PICTURE | GIF | VIDEO | ARCH_TEXT  |
                  SIMPLE_RSS | HTML | STREAM_MEDIA | BOX | WEATHER | 
                  DRAWN_DIGITAL_CLOCK |  RT_MEDIA)

                  Each element in the array is dynamic type (AnyOf), it may be
                  any of the following subtypes, specifically according to the
                  type within it.
            x-apifox-refs: {}
            x-apifox-orders:
              - name
              - schedules
              - widgets
            required:
              - name
              - widgets
            description: contents to be played
          description: A collection of the contents to be played.
      x-apifox-orders:
        - playerIds
        - schedule
        - pages
        - noticeUrl
      required:
        - playerIds
        - pages
      x-apifox-folder: ''
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```

# Hand drawn digital clock

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths: {}
components:
  schemas:
    Hand drawn digital clock:
      type: object
      properties:
        type:
          type: string
          description: >-
            Component type: PICTURE, VIDEO, ARCH_TEXT, SIMPLE_RSS, HTML,
            STREAM_MEDIA,  BOX, WEATHER
        zIndex:
          type: integer
          description: >-
            Component overlay order, the larger the number, the upper the layer,
            default 0
        layout:
          type: object
          properties:
            x:
              type: string
              description: >-
                The component's position relative to the left side of the page,
                such as: 10%
            'y':
              type: string
              description: >-
                The component's position relative to the top of the page, such
                as: 10%
            width:
              type: string
              description: 'The width of the component relative to the page, such as: 100%'
            height:
              type: string
              description: 'The height of the component relative to the page, such as: 100%'
          x-apifox-orders:
            - x
            - 'y'
            - width
            - height
          description: The location of the component on the page
          required:
            - x
            - height
            - width
            - 'y'
        zone:
          type: string
          description: IANA Time Zone Identifier, e.g.  "America/Los_Angeles"
        gmt:
          type: string
          description: GMT Time Zone, e.g. "GMT-08:00"
        regular:
          type: string
          description: >-
            The display rules of the digital clock and the placeholder are
            defined as follows: 

            \$dd: represents the day;

            \$MM: Represents the month;

            \$yyyy  Represents the year in 4 digits, while \$yy in 2 digits;

            \$E: A placeholder for the day of the week;

            \$HH: hour, in 24-hour format;

            \$hh: hour, in 12-hour format;

            \$mm: minutes;

            \$ss: seconds;

            \$N: morning or afternoon;

            \\n: Lines are wrapped;
        weekTemplates:
          type: array
          items:
            type: string
          description: >-
            Display template for the week, seven data items, representing Monday
            to Sunday respectively
        suffixTemplates:
          type: array
          items:
            type: string
          description: Display template for morning and afternoon
        textColor:
          type: string
          description: 'The foreground color of the text, the default #FF0000'
        fontSize:
          type: integer
          description: Font size by pixels, the default is 16
        fontFamily:
          type: array
          items:
            type: string
          description: >-
            Font type array, when there are multiple fonts, the first one takes
            precedence, if the first one is invalid, then the following font is
            taken in turn, if there is no such font, the system default one is
            used. For example: ["Times","Georia","New York"]
        fontStyle:
          type: string
          description: 'Font type: 1.BOLD, 2.NORMAL, 3.ITALIC, 4.BOLD_ITALIC'
        fontIsUnderline:
          type: boolean
          description: Is the font underlined or not
        backgroundColor:
          type: string
          description: 'Background color, default #00FFFFFF'
        shadowEnable:
          type: boolean
          description: Whether to enable shadows, default is false
        shadowRadius:
          type: integer
          description: Shadow radius size in pixels
        shadowDx:
          type: integer
          description: Shadow offset of the x-axis
        shadowDy:
          type: integer
          description: Shadow offset of the y-axis
        shadowColor:
          type: string
          description: Shadow color
      x-apifox-orders:
        - zIndex
        - type
        - zone
        - gmt
        - regular
        - weekTemplates
        - suffixTemplates
        - textColor
        - fontSize
        - fontFamily
        - fontStyle
        - fontIsUnderline
        - backgroundColor
        - shadowEnable
        - shadowRadius
        - shadowDx
        - shadowDy
        - shadowColor
        - layout
      required:
        - layout
        - suffixTemplates
        - weekTemplates
        - regular
        - gmt
        - type
        - fontStyle
        - fontIsUnderline
        - backgroundColor
        - shadowEnable
        - shadowDy
        - shadowDx
        - shadowRadius
        - shadowColor
        - zone
        - textColor
        - fontSize
        - fontFamily
        - zIndex
      description: Components on the page
      x-apifox-folder: ''
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Environmental Monitoring item

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths: {}
components:
  schemas:
    Environmental Monitoring item:
      type: object
      properties:
        zIndex:
          type: integer
          description: >-
            Layer order of a widget. The greater the number, the upper the
            layer. It defaults to 0.
        type:
          type: string
          description: RT_MEDIA
        fontFamily:
          type: string
          description: |-
            Text Font (Default: Arial)
            Supported Player Fonts:
            SimSun
            Microsoft YaHei
            KaiTi
            Arial
            Wingdings 2
            Calibri
        labelFontSize:
          type: integer
          description: Label Font Size. Range:9~256
        valueFontSize:
          type: integer
          description: Data Font Size. Range:9~256
        unitFontSize:
          type: integer
          description: Unit Font Size. Range:9~256
        bold:
          type: boolean
          description: 'Bold (Default: false)'
        italic:
          type: boolean
          description: 'Italic (Default: false)'
        underline:
          type: boolean
          description: 'Underline (Default: false)'
        textColor:
          type: string
          description: 'Font Color: #00FFD4'
        refreshPeriod:
          type: integer
          description: 'Data Refresh Interval (Unit: ms)'
        style:
          type: integer
          description: >-
            Playback Style (Default: 1, Options: 1-Style1, 2-Style2, 3-Style3,
            4-Style4)
        playMode:
          type: string
          description: 'Play Mode，(Default: STATIC, Options: STATI, SCROLL)'
        seedByPixelEnable:
          type: boolean
          description: 'Enable Pixel Scrolling (true: Pixel, false: Gear)'
        speedGear:
          type: integer
          description: >-
            Gear Level (Effective when playMode=Scroll AND
            seedByPixelEnable=false, Range: 1~10)
        speedPixel:
          type: integer
          description: >-
            Pixel Value (Effective when playMode=Scroll AND
            seedByPixelEnable=true, Range: 10~500px)
        customLabel:
          type: object
          properties:
            NOI:
              type: object
              properties:
                name:
                  type: string
                  description: Display name
                enable:
                  type: boolean
                  description: Enable or not，false-not，true-enable
                unit:
                  type: string
                  description: unit，Recommended Value：ions/cm³，Supports customization
              required:
                - name
                - enable
                - unit
              x-apifox-orders:
                - name
                - enable
                - unit
              description: NAI
            UVR:
              type: object
              properties:
                name:
                  type: string
                  description: Display name
                enable:
                  type: boolean
                  description: Enable or not，false-not，true-enable
                unit:
                  type: string
                  description: unit，Recommended Value：nm，Supports customization
              required:
                - name
                - enable
                - unit
              x-apifox-orders:
                - name
                - enable
                - unit
              description: UV Radiation
            airHumidity:
              type: object
              properties:
                name:
                  type: string
                  description: Display name
                enable:
                  type: boolean
                  description: Enable or not，false-not，true-enable
                unit:
                  type: string
                  description: Unit，Recommended Value：%RH，Supports customization
              required:
                - name
                - enable
                - unit
              x-apifox-orders:
                - name
                - enable
                - unit
              description: airHumidity
            airPressure:
              type: object
              properties:
                name:
                  type: string
                  description: 气压显示名称
                enable:
                  type: boolean
                  description: 是否显示，false-不显示，true-显示
                type:
                  type: integer
                  description: >-
                    气压数据类型，枚举值：0-KPa 1-bar 2-atm 3-mmHg 4-Torr
                    5-kgf/cm2，6-hpa，默认：0
                unit:
                  type: string
                  description: 温度单位，请对应数据类型传参，例如，type=0，unitName=KPa
              required:
                - name
                - enable
                - type
                - unit
              x-apifox-orders:
                - name
                - enable
                - type
                - unit
              description: airPressure
            ambiantLight:
              type: object
              properties:
                name:
                  type: string
                  description: Display name
                enable:
                  type: boolean
                  description: Enable or not，false-not，true-enable
                unit:
                  type: string
                  description: Unit，Recommended Value：Lux，Supports customization
              required:
                - name
                - enable
                - unit
              x-apifox-orders:
                - name
                - enable
                - unit
              description: 'Illuminance '
            coII:
              type: object
              properties:
                name:
                  type: string
                  description: Display name
                enable:
                  type: boolean
                  description: Enable or not，false-not，true-enable
                unit:
                  type: string
                  description: Unit，Recommended Value：ppm，Supports customization
              required:
                - name
                - enable
                - unit
              x-apifox-orders:
                - name
                - enable
                - unit
              description: Carbon Dioxide
            noise:
              type: object
              properties:
                name:
                  type: string
                  description: Display name
                enable:
                  type: boolean
                  description: Enable or not，false-not，true-enable
                unit:
                  type: string
                  description: Unit，Recommended Value：db，Supports customization
              required:
                - name
                - enable
                - unit
              x-apifox-orders:
                - name
                - enable
                - unit
              description: noise
            pmC:
              type: object
              properties:
                name:
                  type: string
                  description: Display name
                enable:
                  type: boolean
                  description: Enable or not，false-not，true-enable
                unit:
                  type: string
                  description: Unit，Recommended Value：μg/m3，Supports customization
              required:
                - name
                - enable
                - unit
              x-apifox-orders:
                - name
                - enable
                - unit
              description: PM100
            pmIIV:
              type: object
              properties:
                name:
                  type: string
                  description: Display name
                enable:
                  type: boolean
                  description: Enable or not，false-not，true-enable
                unit:
                  type: string
                  description: Unit，Recommended Value：μg/m3，Supports customization
              required:
                - name
                - enable
                - unit
              x-apifox-orders:
                - name
                - enable
                - unit
              description: PM2.5
            pmX:
              type: object
              properties:
                name:
                  type: string
                  description: Display name
                enable:
                  type: boolean
                  description: Enable or not，false-not，true-enable
                unit:
                  type: string
                  description: Unit，Recommended Value：μg/m3，Supports customization
              required:
                - name
                - enable
                - unit
              x-apifox-orders:
                - name
                - enable
                - unit
              description: PM10
            rainfall:
              type: object
              properties:
                name:
                  type: string
                  description: Display name
                enable:
                  type: boolean
                  description: Enable or not，false-not，true-enable
                unit:
                  type: string
                  description: Unit，Recommended Value：mm，Supports customization
              required:
                - name
                - enable
                - unit
              x-apifox-orders:
                - name
                - enable
                - unit
              description: Rainfall
            snowfall:
              type: object
              properties:
                name:
                  type: string
                  description: Display name
                enable:
                  type: boolean
                  description: Enable or not，false-not，true-enable
                unit:
                  type: string
                  description: Unit，Recommended Value：mm，Supports customization
              required:
                - name
                - enable
                - unit
              x-apifox-orders:
                - name
                - enable
                - unit
              description: Snowfall
            soilMoisture:
              type: object
              properties:
                name:
                  type: string
                  description: Display name
                enable:
                  type: boolean
                  description: Enable or not，false-not，true-enable
                unit:
                  type: string
                  description: Unit，Recommended Value：RH，Supports customization
              required:
                - name
                - enable
                - unit
              x-apifox-orders:
                - name
                - enable
                - unit
              description: Soil Moisture
            soilPH:
              type: object
              properties:
                name:
                  type: string
                  description: Display name
                enable:
                  type: boolean
                  description: Enable or not，false-not，true-enable
                unit:
                  type: string
                  description: Unit，Recommended Value：pH，Supports customization
              required:
                - name
                - enable
                - unit
              x-apifox-orders:
                - name
                - enable
                - unit
              description: Soil pH
            soilTemperature:
              type: object
              properties:
                name:
                  type: string
                  description: Display name
                enable:
                  type: boolean
                  description: Enable or not，false-not，true-enable
                type:
                  type: integer
                  description: >-
                    Soil Temperature Data Type, 0: Celsius (°C) (default)  1:
                    Fahrenheit (°F)
                unit:
                  type: string
                  description: >-
                    Unit，Please pass parameters corresponding to the data
                    type，For example, type=0, unit=℃
              required:
                - name
                - enable
                - type
                - unit
              x-apifox-orders:
                - name
                - enable
                - type
                - unit
              description: Soil Temperature
            sunshineDuration:
              type: object
              properties:
                name:
                  type: string
                  description: Display name
                enable:
                  type: boolean
                  description: Enable or not，false-not，true-enable
                unit:
                  type: string
                  description: Unit，Recommended Value：W/m2，Supports customization
              required:
                - name
                - enable
                - unit
              x-apifox-orders:
                - name
                - enable
                - unit
              description: Sunshine Duration
            temperature:
              type: object
              properties:
                name:
                  type: string
                  description: Display name
                enable:
                  type: boolean
                  description: Enable or not，false-not，true-enable
                type:
                  type: integer
                  description: >-
                    Temperature Data Type, 0: Celsius (°C) (default)  1:
                    Fahrenheit (°F)
                unit:
                  type: string
                  description: >-
                    Unit，Please pass parameters corresponding to the data
                    type，For example, type=0, unit=℃
                tempCompensate:
                  type: integer
                  description: Compensate，Default：0，Range：-50~50
              required:
                - name
                - enable
                - type
                - unit
                - tempCompensate
              x-apifox-orders:
                - name
                - enable
                - type
                - unit
                - tempCompensate
              description: temperature
            windDirection:
              type: object
              properties:
                name:
                  type: string
                  description: Display name
                enable:
                  type: boolean
                  description: Enable or not，false-not，true-enable
                templates:
                  type: array
                  items:
                    type: string
                  description: >-
                    this field is mandatory when wind direction data is
                    available. Values must strictly follow the predefined order
                    below:
                    North,Northeast,East,Southeast,South,Southwest,West,Northwest
              required:
                - name
                - enable
                - templates
              x-apifox-orders:
                - name
                - enable
                - templates
              description: Wind Direction
            windSpeed:
              type: object
              properties:
                name:
                  type: string
                  description: Display name
                enable:
                  type: boolean
                  description: Enable or not，false-not，true-enable
                type:
                  type: integer
                  description: >-
                    Wind Speed Data Type，0: Kilometers Per Hour, 1: Meters Per
                    Second, 2: level
                unit:
                  type: string
                  description: >-
                    Unit，Please pass parameters corresponding to the data
                    type，For example, type=0, unit=km/h
              required:
                - name
                - enable
                - type
                - unit
              x-apifox-orders:
                - name
                - enable
                - type
                - unit
              description: Wind Speed
          required:
            - NOI
            - UVR
            - airHumidity
            - airPressure
            - ambiantLight
            - coII
            - noise
            - pmC
            - pmIIV
            - pmX
            - rainfall
            - snowfall
            - soilMoisture
            - soilPH
            - soilTemperature
            - sunshineDuration
            - temperature
            - windDirection
            - windSpeed
          x-apifox-orders:
            - NOI
            - UVR
            - airHumidity
            - airPressure
            - ambiantLight
            - coII
            - noise
            - pmC
            - pmIIV
            - pmX
            - rainfall
            - snowfall
            - soilMoisture
            - soilPH
            - soilTemperature
            - sunshineDuration
            - temperature
            - windDirection
            - windSpeed
          description: Item Attribute Structure
        duration:
          type: integer
          description: Effect duration (millisecond)
        layout:
          type: object
          properties:
            x:
              type: string
              description: >-
                Position of a widget relative to the left side of the page,
                example: 10%.
            'y':
              type: string
              description: >-
                Position of a widget relative to the top of the page, example:
                10%.
            width:
              type: string
              description: 'Widget width relative to the page, example: 100%.'
            height:
              type: string
              description: 'Widget height relative to the page, example: 100%.'
          required:
            - x
            - 'y'
            - width
            - height
          x-apifox-orders:
            - x
            - 'y'
            - width
            - height
          description: Widget position on a page
      required:
        - zIndex
        - type
        - fontFamily
        - labelFontSize
        - valueFontSize
        - unitFontSize
        - bold
        - italic
        - underline
        - textColor
        - refreshPeriod
        - style
        - playMode
        - seedByPixelEnable
        - speedGear
        - speedPixel
        - customLabel
        - duration
        - layout
      x-apifox-orders:
        - zIndex
        - type
        - fontFamily
        - labelFontSize
        - valueFontSize
        - unitFontSize
        - bold
        - italic
        - underline
        - textColor
        - refreshPeriod
        - style
        - playMode
        - seedByPixelEnable
        - speedGear
        - speedPixel
        - customLabel
        - duration
        - layout
      x-apifox-folder: ''
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# Solution widgets base

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths: {}
components:
  schemas:
    Solution widgets base:
      type: object
      properties:
        name:
          type: string
          description: >-
            Widget name. This is used for querying logs. If this is empty, it
            will be difficult to distinguish when you query logs.
        type:
          type: string
          description: >-
            Widget type. Supported Widget Types: (PICTURE | GIF | VIDEO |
            ARCH_TEXT  | SIMPLE_RSS | HTML | STREAM_MEDIA | BOX | WEATHER | 
            DRAWN_DIGITAL_CLOCK |  RT_MEDIA)
        md5:
          type: string
          description: >-
            The image and video are required. The content is the md5 value of
            the image and video.
        size:
          type: number
          description: Image or video size (byte)
        duration:
          type: number
          description: Playback duration of a widget which is accurate to millisecond.
        url:
          type: string
          description: >-
            Image\GIF\Video\RSS\Web page\Streaming media URL, You are required
            to verify the URL validity.
        zIndex:
          type: integer
          description: >-
            Layer order of a widget. The greater the number, the upper the
            layer. It defaults to 0.
        layout:
          type: object
          properties:
            x:
              type: string
              description: >-
                Position of a widget relative to the left side of the page,
                example: 10%.
            'y':
              type: string
              description: >-
                Position of a widget relative to the top of the page, example:
                10%.
            width:
              type: string
              description: 'Widget width relative to the page, example: 100%.'
            height:
              type: string
              description: 'Widget height relative to the page, example: 100%.'
          x-apifox-orders:
            - x
            - 'y'
            - width
            - height
          description: Widget position on a page
          required:
            - x
            - height
            - width
            - 'y'
        inAnimation:
          type: object
          properties:
            type:
              type: string
              description: Effect type NONE-No effect RANDOM-Random For others
            duration:
              type: number
              description: Effect duration (millisecond)
          x-apifox-orders:
            - type
            - duration
          required:
            - type
            - duration
          description: Entrance effect of a widget. No effect by default.
        mediaList:
          type: array
          items:
            type: string
            description: Content in the window
          description: >-
            Content in the window. This is required by windows. See widget
            properties or the sample below.
        offline:
          type: object
          properties:
            url:
              type: string
              description: >-
                Download address of offline web file. All the css styles and js
                scripts must be written to the web page. Externally referenced
                css and js cannot be downloaded offline.
            size:
              type: number
              description: Offline web file size
            md5:
              type: string
              description: md5 value of the offline web file
          x-apifox-orders:
            - url
            - size
            - md5
          required:
            - url
            - md5
            - size
          description: Offline html widget playback media (custom function)
      x-apifox-orders:
        - zIndex
        - name
        - type
        - md5
        - size
        - duration
        - url
        - layout
        - inAnimation
        - mediaList
        - offline
      required:
        - layout
        - url
        - duration
        - size
        - md5
        - type
      description: Widgets on a page
      x-apifox-folder: ''
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```

# Text component

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths: {}
components:
  schemas:
    Text component:
      type: object
      properties:
        horizontalAlignment:
          type: string
          description: >-
            Horizontal alignment: LEFT - Left aligned (default), CENTER -
            Centered, RIGHT - Right aligned
          default: LEFT
        verticalAlignment:
          type: string
          description: >-
            Vertical alignment: TOP - Top aligned (default), CENTER - Centered,
            BOTTOM - Bottom aligned
          default: TOP
        displayType:
          type: string
          description: >-
            Display mode: PAGE_SWITCH - Page flipping, SCROLL - Scrolling,
            STATIC - Static (if text content exceeds screen size, only first
            screen is displayed)
        scrollAttribute:
          type: object
          description: Required when display type is 'SCROLL'
          properties:
            animation:
              type: string
              description: >-
                Scroll direction: MARQUEE_LEFT - Scroll left, MARQUEE_UP -
                Scroll up
            speed:
              type: integer
              description: 'Scroll speed level, range: 1~10'
              minimum: 1
              maximum: 10
          x-apifox-orders:
            - animation
            - speed
          required:
            - animation
            - speed
        pageSwitchAttribute:
          type: object
          description: Required when display type is 'PAGE_SWITCH'
          properties:
            inAnimation:
              type: object
              description: 'Page transition entrance effect, default: no effect'
              properties: {}
              x-apifox-orders: []
            remainDuration:
              type: integer
              description: Duration per page in milliseconds
          x-apifox-orders:
            - inAnimation
            - remainDuration
          required:
            - remainDuration
        backgroundColor:
          type: string
          description: 'Overall text background color, #00000000 represents transparent'
          default: '#00000000'
        lines:
          type: array
          description: Collection of multi-line text displays
          items:
            type: object
            properties:
              textAttributes:
                type: array
                description: Text content and formatting
                items:
                  type: object
                  properties:
                    content:
                      type: string
                      description: Text content
                    fontFamily:
                      type: string
                      description: >-
                        Font family (default: Arial). Supported fonts: SimSun -
                        Song typeface, Microsoft YaHei - Microsoft YaHei, KaiTi
                        - Kai typeface, Arial - Arial font, Wingdings2, Calibri.
                        If above fonts don't work, use Chinese names directly:
                        微软雅黑, 宋体, etc.
                      default: Arial
                    fontSize:
                      type: integer
                      description: 'Font size, range: 9~256'
                      minimum: 9
                      maximum: 256
                    textColor:
                      type: string
                      description: 'Text color, e.g.: #000000 represents black'
                    isBold:
                      type: boolean
                      description: 'Whether text is bold, default: false'
                      default: false
                    isUnderline:
                      type: boolean
                      description: 'Whether text has underline, default: false'
                      default: false
                    backgroundColor:
                      type: string
                      description: >-
                        Text background color, 16-digit color value, default
                        transparent, #00000000 represents transparent
                      default: '#00000000'
                  x-apifox-orders:
                    - content
                    - fontFamily
                    - fontSize
                    - textColor
                    - isBold
                    - isUnderline
                    - backgroundColor
                  required:
                    - content
                    - fontSize
                    - textColor
            x-apifox-orders:
              - textAttributes
            required:
              - textAttributes
      x-apifox-orders:
        - horizontalAlignment
        - verticalAlignment
        - displayType
        - scrollAttribute
        - pageSwitchAttribute
        - backgroundColor
        - lines
      required:
        - displayType
        - scrollAttribute
        - pageSwitchAttribute
        - lines
      x-apifox-folder: ''
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```

# RSS component

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths: {}
components:
  schemas:
    RSS component:
      type: object
      properties:
        displayType:
          type: string
          description: 'RSS display mode: PAGE_SWITCH - Page flipping, SCROLL - Scrolling'
        updatePeriod:
          type: integer
          description: RSS page update frequency in milliseconds, minimum 10 seconds
          minimum: 10000
        titleEnable:
          type: boolean
          description: Whether to display RSS title
        pubTimeEnable:
          type: boolean
          description: Whether to display RSS publication time
        bodyEnable:
          type: boolean
          description: Whether to display RSS content body
        bodyImageEnable:
          type: boolean
          description: 'Whether to display images in RSS content, default: false'
          default: false
        pageSwitchAttribute:
          type: object
          description: Required when display type is 'PAGE_SWITCH'
          properties:
            inAnimation:
              type: object
              description: 'Page transition entrance effect, default: no effect'
              properties: {}
              x-apifox-orders: []
            remainDuration:
              type: integer
              description: Duration per page in milliseconds
          x-apifox-orders:
            - inAnimation
            - remainDuration
          required:
            - remainDuration
        scrollAttribute:
          type: object
          description: Required when display type is 'SCROLL'
          properties:
            animation:
              type: string
              description: >-
                Scroll direction: MARQUEE_LEFT - Scroll left, MARQUEE_UP -
                Scroll up
            speed:
              type: integer
              description: 'Scroll speed level, range: 1~10'
              minimum: 1
              maximum: 10
          x-apifox-orders:
            - animation
            - speed
          required:
            - animation
            - speed
        titleTextAttr:
          type: object
          description: Text attributes for title display when enabled
          properties:
            fontFamily:
              type: string
              description: >-
                Font family, default: Microsoft YaHei. Supported fonts: SimSun -
                Song typeface, Microsoft YaHei - Microsoft YaHei, KaiTi - Kai
                typeface, Arial - Arial font, Wingdings2, Calibri
              default: Microsoft YaHei
            fontSize:
              type: integer
              description: 'Font size, default: 12'
              default: 12
            textColor:
              type: string
              description: 'Text color, default: red, e.g.: #000000 represents black'
              default: '#FF0000'
            isBold:
              type: boolean
              description: 'Whether text is bold, default: false'
              default: false
            isItalic:
              type: boolean
              description: 'Whether text is italic, default: false'
              default: false
            isUnderline:
              type: boolean
              description: 'Whether text has underline, default: false'
              default: false
          x-apifox-orders:
            - fontFamily
            - fontSize
            - textColor
            - isBold
            - isItalic
            - isUnderline
        pubTimeTextAttr:
          type: object
          description: Text attributes for publication time display when enabled
          properties:
            fontFamily:
              type: string
              description: >-
                Font family, default: Microsoft YaHei. Supported fonts: SimSun -
                Song typeface, Microsoft YaHei - Microsoft YaHei, KaiTi - Kai
                typeface, Arial - Arial font, Wingdings2, Calibri
              default: Microsoft YaHei
            fontSize:
              type: integer
              description: 'Font size, default: 12'
              default: 12
            textColor:
              type: string
              description: 'Text color, default: red, e.g.: #000000 represents black'
              default: '#FF0000'
            isBold:
              type: boolean
              description: 'Whether text is bold, default: false'
              default: false
            isItalic:
              type: boolean
              description: 'Whether text is italic, default: false'
              default: false
            isUnderline:
              type: boolean
              description: 'Whether text has underline, default: false'
              default: false
          x-apifox-orders:
            - fontFamily
            - fontSize
            - textColor
            - isBold
            - isItalic
            - isUnderline
        bodyTextAttr:
          type: object
          description: Text attributes for content body display when enabled
          properties:
            fontFamily:
              type: string
              description: >-
                Font family, default: Microsoft YaHei. Supported fonts: SimSun -
                Song typeface, Microsoft YaHei - Microsoft YaHei, KaiTi - Kai
                typeface, Arial - Arial font, Wingdings2, Calibri
              default: Microsoft YaHei
            fontSize:
              type: integer
              description: 'Font size, default: 12'
              default: 12
            textColor:
              type: string
              description: 'Text color, default: red, e.g.: #000000 represents black'
              default: '#FF0000'
            isBold:
              type: boolean
              description: 'Whether text is bold, default: false'
              default: false
            isItalic:
              type: boolean
              description: 'Whether text is italic, default: false'
              default: false
            isUnderline:
              type: boolean
              description: 'Whether text has underline, default: false'
              default: false
          x-apifox-orders:
            - fontFamily
            - fontSize
            - textColor
            - isBold
            - isItalic
            - isUnderline
      x-apifox-orders:
        - displayType
        - updatePeriod
        - titleEnable
        - pubTimeEnable
        - bodyEnable
        - bodyImageEnable
        - pageSwitchAttribute
        - scrollAttribute
        - titleTextAttr
        - pubTimeTextAttr
        - bodyTextAttr
      required:
        - displayType
        - updatePeriod
        - titleEnable
        - pubTimeEnable
        - bodyEnable
        - pageSwitchAttribute
        - scrollAttribute
      x-apifox-folder: ''
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# WEATHER - Simple Weather Component

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths: {}
components:
  schemas:
    WEATHER - Simple Weather Component:
      type: object
      properties:
        address:
          type: string
          description: 'Location address, e.g.: Beijing, Xi''an'
        latitude:
          type: number
          description: Latitude coordinate
          format: float
        longitude:
          type: number
          description: Longitude coordinate
          format: float
        width:
          type: number
          description: >-
            Relative width of weather component box; display effect depends on
            layout parameters. Increase this value if the component box appears
            incomplete on screen
          format: float
        height:
          type: number
          description: >-
            Relative height of weather component box; display effect depends on
            layout parameters. Increase this value if the component box appears
            incomplete on screen
          format: float
        duration:
          type: integer
          description: Playback duration in milliseconds
        refreshPeriod:
          type: integer
          description: Refresh interval in milliseconds
        fontSize:
          type: integer
          description: Font size
        bold:
          type: boolean
          description: 'Whether text is bold, default: false'
          default: false
        italic:
          type: boolean
          description: 'Whether text is italic, default: false'
          default: false
        underline:
          type: boolean
          description: 'Whether text has underline, default: false'
          default: false
        color:
          type: string
          description: 'Text color, format example: #00FFD4 (cyan)'
        tempUnit:
          type: integer
          description: 'Temperature unit: 0 - Celsius, 1 - Fahrenheit'
        unitSymbol:
          type: string
          description: >-
            Unit symbol: 0 - °, 1 - ℃/℉. When tempUnit=0, unitSymbol can be 0 or
            1; when tempUnit=1, unitSymbol must be 1
        weatherEnable:
          type: boolean
          description: Whether to display weather condition section
        tempEnable:
          type: boolean
          description: Whether to display temperature section
        windEnable:
          type: boolean
          description: Whether to display wind section
        humidEnable:
          type: boolean
          description: Whether to display humidity section
        currentTempEnable:
          type: boolean
          description: Whether to display current temperature section
        isShowInOneLine:
          type: boolean
          description: Whether to display in single line
      x-apifox-orders:
        - address
        - latitude
        - longitude
        - width
        - height
        - duration
        - refreshPeriod
        - fontSize
        - bold
        - italic
        - underline
        - color
        - tempUnit
        - unitSymbol
        - weatherEnable
        - tempEnable
        - windEnable
        - humidEnable
        - currentTempEnable
        - isShowInOneLine
      required:
        - address
        - latitude
        - longitude
        - width
        - height
        - duration
        - refreshPeriod
        - fontSize
        - bold
        - italic
        - color
        - underline
        - tempUnit
        - unitSymbol
        - weatherEnable
        - tempEnable
        - windEnable
        - humidEnable
        - currentTempEnable
        - isShowInOneLine
      x-apifox-folder: ''
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# ANALOG_WEATHER - Basic Weather Component

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths: {}
components:
  schemas:
    ANALOG_WEATHER - Basic Weather Component:
      type: object
      properties:
        lang:
          type: string
          description: >-
            Language setting: zh - Chinese; en - English; jp - Japanese; es -
            Spanish; fr - French; pt - Portuguese; it - Italian
        address:
          type: string
          description: 'Location address, e.g.: Beijing, Xi''an'
        latitude:
          type: number
          description: Latitude coordinate
          format: float
        longitude:
          type: number
          description: Longitude coordinate
          format: float
        width:
          type: number
          description: >-
            Relative width of weather component box; display effect depends on
            layout parameters. Increase this value if the component box appears
            incomplete on screen
          format: float
        height:
          type: number
          description: >-
            Relative height of weather component box; display effect depends on
            layout parameters. Increase this value if the component box appears
            incomplete on screen
          format: float
        duration:
          type: integer
          description: Playback duration in milliseconds
        refreshPeriod:
          type: integer
          description: Refresh interval in milliseconds, recommended minimum 10 seconds
          minimum: 10000
        tempUnit:
          type: integer
          description: 'Temperature unit: 0 - Celsius, 1 - Fahrenheit'
      x-apifox-orders:
        - lang
        - address
        - latitude
        - longitude
        - width
        - height
        - duration
        - refreshPeriod
        - tempUnit
      required:
        - lang
        - address
        - latitude
        - longitude
        - width
        - height
        - duration
        - refreshPeriod
        - tempUnit
      x-apifox-folder: ''
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```


# ADVANCED_WEATHER - Advanced Weather Component

## OpenAPI Specification

```yaml
openapi: 3.0.1
info:
  title: ''
  description: ''
  version: 1.0.0
paths: {}
components:
  schemas:
    ADVANCED_WEATHER - Advanced Weather Component:
      type: object
      properties:
        lang:
          type: string
          description: >-
            Language setting: zh - Chinese; en - English; jp - Japanese; es -
            Spanish; fr - French; pt - Portuguese; it - Italian
        module:
          type: string
          description: 'Display style: 1 - Style 1, 2 - Style 2, 3 - Style 3, 4 - Style 4'
        address:
          type: string
          description: 'Location address, e.g.: Beijing, Xi''an'
        latitude:
          type: number
          description: Latitude coordinate
          format: float
        longitude:
          type: number
          description: Longitude coordinate
          format: float
        width:
          type: number
          description: >-
            Relative width of weather component box; display effect depends on
            layout parameters. Increase this value if the component box appears
            incomplete on screen
          format: float
        height:
          type: number
          description: >-
            Relative height of weather component box; display effect depends on
            layout parameters. Increase this value if the component box appears
            incomplete on screen
          format: float
        duration:
          type: integer
          description: Playback duration in milliseconds
        pageDuration:
          type: integer
          description: Page flip duration per page in milliseconds
        refreshPeriod:
          type: integer
          description: Refresh interval in milliseconds
        tempUnit:
          type: integer
          description: 'Temperature unit: 0 - Celsius, 1 - Fahrenheit'
        basicInfo:
          type: boolean
          description: Whether to display basic weather information
        airQuality:
          type: boolean
          description: Whether to display air quality information (China region only)
        comfort:
          type: boolean
          description: Whether to display comfort index
        windSpeed:
          type: boolean
          description: Whether to display wind speed and atmospheric pressure
        sunrise:
          type: boolean
          description: Whether to display sunrise and sunset times
        living:
          type: boolean
          description: Whether to display living index (China region only)
      x-apifox-orders:
        - lang
        - module
        - address
        - latitude
        - longitude
        - width
        - height
        - duration
        - pageDuration
        - refreshPeriod
        - tempUnit
        - basicInfo
        - airQuality
        - comfort
        - windSpeed
        - sunrise
        - living
      required:
        - lang
        - module
        - address
        - latitude
        - longitude
        - width
        - height
        - duration
        - pageDuration
        - refreshPeriod
        - tempUnit
        - basicInfo
        - airQuality
        - comfort
        - windSpeed
        - sunrise
        - living
      x-apifox-folder: ''
  securitySchemes: {}
servers:
  - url: https://open-au.vnnox.com
    description: AU
  - url: https://open-us.vnnox.com
    description: US
  - url: https://open-eu.vnnox.com
    description: EU
  - url: https://open-in.vnnox.com
    description: IN
security: []

```
